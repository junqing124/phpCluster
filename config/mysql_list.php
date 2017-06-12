<?php
include_once( "../include/common.inc.php" );
$page_title = 'Mysql列表';
$cls_data_mc = new cls_data('c_mysql_config');
if( 'mysql_add' == $action )
{
    if( empty( $mysql_host ) || empty( $mysql_port ) || empty( $mysql_user ) || empty( $mysql_password ) )
    {
        show_msg( '请写全配置', 2 );
    }else
    {
        $info = array(
            'mc_host'=> $mysql_host,
            'mc_port'=> $mysql_port,
            'mc_user'=> $mysql_user,
            'mc_password'=> $mysql_password,
            'mc_add_time'=> time(),
        );
        if( $cls_data_mc->insert_ex( $info ) )
        {
        }else
        {
            show_msg( '添加失败' . $cls_data_mc->get_error(), 2 );
        }
    }
}
if( 'mysql_edit' == $action )
{
    $info = array(
        'mc_host'=> $mysql_host,
        'mc_port'=> $mysql_port,
        'mc_user'=> $mysql_user,
        'mc_update_time'=> time(),
    );

    if( $mysql_password )
    {
        $info['mc_password'] = $mysql_password;
    }
    $cls_data_mc->update_one( $info, "mc_id={$id}" );
    echo $cls_data_mc->get_last_sql();
}
if( 'mysql_del' == $action )
{
    $linux_info_detail = $cls_data_mc->delete_ex( "mc_id={$id}" );
}
if( 'mysql_edit_detail' == $action )
{
    $mysql_info_detail = $cls_data_mc->select_one_ex( array( 'where'=> "mc_id={$id}" ) );
}
if( 'mysql_config' == $action )
{
    if( $mc_id )
    {
        foreach( $mc_id as $mc_key_id )
        {
            $info = array( 'mc_is_index_processlist'=> $is_index_processlist[$mc_key_id], 'mc_refresh_processlist_sec'=> $fresh_index_processlist[$mc_key_id] ? $fresh_index_processlist[$mc_key_id] : 10 );
            $cls_data_mc->update_one( $info, "mc_id={$mc_key_id}" );
        }
    }
}
$mysql_list = $cls_data_mc->select_ex();
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

                <div class="btn-toolbar">

                    <div class="btn-group">
                    </div>
                </div>
                <div class="well"><form action="#" method="post">
                        <input type="hidden" value="<?php echo 'mysql_edit_detail' == $action ? 'mysql_edit' : 'mysql_add'; ?>" name="action">
                        <input type="hidden" value="<?php echo $id ?>" name="id">
                        地址:<input name="mysql_host" value="<?php echo $mysql_info_detail['mc_host'] ?>" class="input-small" type="text">
                        端口:<input name="mysql_port" value="<?php echo $mysql_info_detail['mc_port'] ?>" class="input-small" type="text">
                        用户:<input name="mysql_user" value="<?php echo $mysql_info_detail['mc_user'] ?>" class="input-small" type="text">
                        密码:<input name="mysql_password" value="" class="input-small" type="text">
                        <button class="btn btn-primary"><i class="icon-plus"></i> <?php echo 'mysql_edit_detail' == $action ? '修改' : '添加'; ?></button>
                        <br>Notice:请先添加对应ip的linux配置,添加用户时要看哪个用户的连接情况,添加哪个用户;
                        <?php
                            if( 'mysql_auto_killed' == $action )
                            {
                                echo "<br>在crontab -e添加:<br>*/1 * * * * php " . __DIR__ . "/crontab/kill_mysql_processlist.php {$id} 60";
                                echo '<br>后面的60表示自动kill运行多久的process';
                            }
                        ?>
                    </form>
                    <form action="#" method="post">
                        <input type="hidden" value="mysql_config" name="action">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>地址</th>
                            <th>用户</th>
                            <th>首页显示processlist</th>
                            <th>首页自动刷新processlist</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach( $mysql_list as $mysql_info )
                            {
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" value="<?php echo $mysql_info['mc_id'] ?>" name="mc_id[<?php echo $mysql_info['mc_id'] ?>]">
                                <?php echo $mysql_info['mc_id'] ?></td>
                            <td><?php echo $mysql_info['mc_host'] ?></td>
                            <td><?php echo $mysql_info['mc_user'] ?></td>
                            <td><label><input <?php if( $mysql_info['mc_is_index_processlist'] ){ echo 'checked'; } ?> value="1" type="checkbox" name="is_index_processlist[<?php echo $mysql_info['mc_id'] ?>]">显示</label></td>
                            <td><label><input name="fresh_index_processlist[<?php echo $mysql_info['mc_id'] ?>]" style="width: 30px;" value="<?php echo $mysql_info['mc_refresh_processlist_sec'] ?>">秒</label></td>
                            <td>
                                <a href="?action=mysql_edit_detail&id=<?php echo $mysql_info['mc_id'] ?>">修改</a>
                                <a onclick="return confirm('确定删除[<?php echo $mysql_info['mc_host']; ?>]?')" href="?action=mysql_del&id=<?php echo $mysql_info['mc_id'] ?>">删除</a>
                                <a href="?action=mysql_auto_killed&id=<?php echo $mysql_info['mc_id'] ?>">自动kill长时间运行的mysql thread</a>
                            </td>
                        </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary"><i class="icon-plus"></i> 保存</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once( '../footer.php' );
    ?>
  </body>
</html>