<?php
include_once( "../include/common.inc.php" );

$cls_data_mc = new cls_data('c_mysql_config');
$error_msg = '';

if( 'mysql_select' == $type )
{
    $page_title = '执行select ';
    if( $select_sql )
    {
        $select_sql_lower = strtolower( $select_sql );
        if( 'select' != substr( $select_sql_lower, 0, 6 ) )
        {
            $error_msg = '不是select语句';
        }else
        {
            //处理select
            $select_sql = stripslashes( $select_sql );
            $select_sql = str_replace( "\'", '"', $select_sql );
            //开始执行
            $mysql_list = $cls_data_mc->execute( "select mc_id,mc_host,mc_user,mc_password,lc_id,lc_host,lc_user,lc_password from c_mysql_config inner join c_linux_config on mc_host=lc_host where lc_group_id={$group_id}" );
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once( '../header.php' );?>
    <div class="content">
        
        <div class="header">
            <h1 class="page-title"><?php echo $page_title ?></h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li class="active"><?php echo $page_title ?></li>
        </ul>
        <div class="container-fluid">
            <div class="row-fluid">
                <?php if( $error_msg ){ ?>
                <div class="error" style="color:red;">
                    <?php echo $error_msg; ?>
                </div>
                <?php } ?>
                <div class="well">
                    <form action="#" method="post">
                        <input type="hidden" value="type" name="action">
                        select sql:<input name="select_sql" value="<?php echo $select_sql ?>" class="input-large" style="width: 400px;" type="text">
                        <button class="btn btn-primary"><i class="icon-plus"></i>搜索</button>
                    </form>
                    <?php
                    if( $mysql_list )
                    {
                        foreach( $mysql_list as $mysql_info )
                        {
                    ?>
                            <div class="block">
                                <a class="block-heading"><?php echo $mysql_info['mc_host'] ?></a>
                                <div class="block-body collapse in" style="margin-top: 10px;">

                                    <div class="stat-widget-container" style="text-align: left">
                                    <?php
                                        $ssh = new Net_SSH2( $mysql_info['lc_host'] );
                                        if( !$ssh->login( $mysql_info['lc_user'], $mysql_info['lc_password'] ) )
                                        {
                                            $result['ack'] = 0;
                                            $result['error_id'] = 1000;
                                            $result['msg'] = '登陆失败';
                                        } else
                                        {
                                            if( 'mysql_select' == $type )
                                            {
                                                $cmd = "mysql -h {$mysql_info['mc_host']} -u{$mysql_info['mc_user']} -p{$mysql_info['mc_password']} -e '{$select_sql}'";
                                                $msg = $ssh->exec( $cmd );
                                                p_r( $msg );
                                            }
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                        } }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once( '../footer.php' );
    ?>



    
  </body>
</html>