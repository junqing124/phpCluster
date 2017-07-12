<?php
include_once( "../include/common.inc.php" );
$page_title = 'Linux状态';
$cmd = 'top -bcn 1';
$type = $type ? $type : 'top';
switch( $type )
{
    case 'top':
        $cmd = 'top -bn 1';
        break;
    case 'df':
        $cmd = 'df';
        break;
    case 'httpnum':
        $cmd = 'pgrep httpd|wc -l';
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once('../header.php');?>
    <div class="content">
        
        <div class="header">
            <h1 class="page-title">Dashboard</h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li class="active">Dashboard</li>
        </ul>
        <div class="container-fluid">
            <div class="row-fluid">
                    

<div class="row-fluid">

    <div class="alert alert-info">
        <a href="?type=top">Top</a>
        <a href="?type=df">磁盘容量</a>
        <a href="?type=httpnum">HTTP数量</a>
    </div>

    <?php
    //这里是mysql processlist
    $cls_data_mc = new cls_data('c_linux_config');
    $linux_list = $cls_data_mc->execute( "select lc_host,lc_user,lc_password from c_linux_config" );
    ?>
    <?php
    foreach( $linux_list as $linux_info )
    {
        $ssh = new Net_SSH2( $linux_info['lc_host'] );
        if( $ssh->login( $linux_info['lc_user'], $linux_info['lc_password'] ) )
        {
            $msg = $ssh->exec($cmd);
            $result = array();
            if( ! $msg )
            {
                array_push( $result, '获取信息失败' );
            }
            if( 'df' == $type )
            {
                $list = explode( "\n", $msg );
                foreach( $list as $list_str )
                {
                    $tmp = preg_split( "/\s+/", $list_str );
                    $percent = floatval( $tmp[4] );
                    if( $percent > 90 )
                    {
                        array_push( $result, array( 'msg'=> "{$tmp[5]}:{$tmp[4]}" ) );
                    }
                }
            }
            if( 'top' == $type )
            {
                $list = explode( "\n", $msg );
                if( $list )
                {
                    array_push( $result, array( 'msg'=> $list[0] ) );
                    array_push( $result, array( 'msg'=> $list[1] ) );
                    array_push( $result, array( 'msg'=> $list[2] ) );
                    array_push( $result, array( 'msg'=> $list[3] ) );
                    array_push( $result, array( 'msg'=> $list[4] ) );
                    array_push( $result, array( 'msg'=> '<hr>' ) );
                    for( $i = 7; $i < count( $list ); $i ++ )
                    {
                        $color = '';
                        $tmp = preg_split( "/\s+/", $list[$i] );
                        $cpu_num = $tmp[0] ? $tmp[8] : $tmp[9];
                        $memory_num = $tmp[0] ? $tmp[9] : $tmp[10];
                        $user = $tmp[0] ? $tmp[1] : $tmp[2];
                        $cmd_str = $tmp[0] ? $tmp[11] : $tmp[12];
                        if( $cpu_num > 0.01 || $memory_num > 0.01 )
                        {
                            if( $cpu_num > 0.5 || $memory_num > 0.5 )
                            {
                                $color = 'red';
                            }
                            array_push( $result, array( 'color'=> $color, 'msg'=> "{$cmd_str}-{$user}-CPU:{$cpu_num}%-MEM:{$memory_num}%" ) );
                        }
                    }
                }
            }
            if( $result )
            {
    ?>
    <div class="block">
        <a class="block-heading"><?php echo $linux_info['lc_host'] ?></a>
        <div class="block-body collapse in" style="margin-top: 10px;">
            <?php
            ?>
                <div class="stat-widget-container" style="text-align: left">
                        <?php
                        //var_dump( $cmd );
                        //分析各种形态的数据
                        foreach( $result as $str_info )
                        {
                            //p_r( $str_info );
                            if( $str_info['msg'] )
                            {
                                if( '<hr>' == $str_info['msg'] )
                                {
                                    echo '<hr style="margin:10px 0;padding:0;">';
                                }else
                                {
                                    echo "<span style='color:{$str_info['color']}'>{$str_info['msg']}</span>";
                                    echo '<br>';
                                }
                            }
                        }
                        ?>
                </div>
            <?php  ?>
        </div>
    </div>
    <?php } } } ?>

</div>
            </div>
        </div>
    </div>



    <?php
    require_once('../footer.php');
    ?>
    
  </body>
</html>