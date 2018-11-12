<?php
include_once( "../include/common.inc.php" );

$page_title = 'Linux列表';
$cls_data_lc = new cls_data('c_linux_config');
$cls_data_lcg = new cls_data('c_linux_config_group');
if( 'linux_add' == $action )
{
    if( empty( $linux_host ) || empty( $linux_port ) || empty( $linux_user ) || empty( $linux_password ) )
    {
        show_msg( '请写全配置', 2 );
    }else
    {
        $info = array(
            'lc_host'=> $linux_host,
            'lc_port'=> $linux_port,
            'lc_user'=> $linux_user,
            'lc_name'=> $linux_name,
            'lc_group_id'=> $linux_group_id,
            'lc_password'=> $linux_password,
            'lc_add_time'=> time(),
        );
        if( $cls_data_lc->insert_ex( $info ) )
        {
        }else
        {
            show_msg( '添加失败' . $cls_data_lc->get_error(), 2 );
        }
    }
}
if( 'linux_edit_detail' == $action )
{
    $linux_info_detail = $cls_data_lc->select_one_ex( array( 'where'=> "lc_id={$id}" ) );
}
if( 'linux_del' == $action )
{
    $linux_info_detail = $cls_data_lc->delete_ex( "lc_id={$id}" );
}
if( 'linux_edit' == $action )
{
    $info = array(
        'lc_host'=> $linux_host,
        'lc_port'=> $linux_port,
        'lc_user'=> $linux_user,
        'lc_name'=> $linux_name,
        'lc_group_id'=> $linux_group_id,
        'lc_update_time'=> time(),
    );

    if( $linux_password )
    {
        $info['lc_password'] = $linux_password;
    }
    $cls_data_lc->update_one( $info, "lc_id={$id}" );
}
if( 'linux_config' == $action )
{
    if( $lc_id )
    {
        foreach( $lc_id as $lc_key_id )
        {
            $info = array( 'lc_is_index_processlist'=> $is_index_processlist[$lc_key_id], 'lc_refresh_processlist_sec'=> $fresh_index_processlist[$lc_key_id] ? $fresh_index_processlist[$lc_key_id] : 10 );
            $cls_data_lc->update_one( $info, "lc_id={$lc_key_id}" );
        }
    }
}
$where_option = array();
if( $group_id )
{
    $where_option[] = "lc_group_id={$group_id}";
}
$linux_list = $cls_data_lc->select_ex( array( 'where'=> $where_option, 'join'=> "left join c_linux_config_group on lcg_id=lc_group_id" ) );
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
                        <input type="hidden" value="<?php echo 'linux_edit_detail' == $action ? 'linux_edit' : 'linux_add'; ?>" name="action">
                        <input type="hidden" value="<?php echo $linux_info_detail['lc_id'] ?>" name="id">
                        名称:<input required name="linux_name" value="<?php echo $linux_info_detail['lc_name'] ?>" class="input-small" type="text">
                        SSH地址:<input required name="linux_host" value="<?php echo $linux_info_detail['lc_host'] ?>" class="input-small" type="text">
                        端口:<input required name="linux_port" value="<?php echo $linux_info_detail['lc_port'] ?>" class="input-small" type="text">
                        用户:<input required name="linux_user" value="<?php echo $linux_info_detail['lc_user'] ?>" class="input-small" type="text">
                        密码:<input name="linux_password" value="" class="input-small" type="text">
                        分组:<select style="width: 100px;" required name="linux_group_id" id="linux_group_id">
                            <option value="">请选择</option>
                            <?php
                            $linux_group_list = $cls_data_lcg->select_ex();
                            foreach( $linux_group_list as $group_info )
                            {
                                echo "<option value='{$group_info['lcg_id']}'>{$group_info['lcg_name']}</option>";
                            }
                            ?>
                        </select>
                        <?php select_value( $linux_group_id, 'linux_group_id' ) ?>
                        <button class="btn btn-primary"><i class="icon-plus"></i> <?php echo 'linux_edit_detail' == $action ? '修改' : '添加'; ?></button>
                        Linux主机请改/etc/ssh/sshd_config里的UseDns为no，加快连接速度
                    </form>
                    <div><a href="?check_ssh=1">测试服务器是否通</a> 分组:
                        <?php
                        $linux_group_list = $cls_data_lcg->select_ex();
                        foreach( $linux_group_list as $group_info )
                        {
                            echo " <a href='?group_id={$group_info['lcg_id']}'>{$group_info['lcg_name']}</a>";
                        }
                        ?></div>
                    <form action="#" method="post">
                        <input type="hidden" value="linux_config" name="action">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>信息</th>
                            <th>SSH测试</th>
                            <th>分组</th>
                            <!--<th>首页显示processlist</th>
                            <th>首页自动刷新processlist</th>-->
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach( $linux_list as $linux_info )
                            {
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" value="<?php echo $linux_info['lc_id'] ?>" name="lc_id[<?php echo $linux_info['lc_id'] ?>]">
                                <?php echo $linux_info['lc_id'] ?></td>
                            <td><?php echo $linux_info['lc_name'] ?>[<?php echo $linux_info['lc_host'] ?>]</td>
                            <td>
                                <?php
                                if( $check_ssh )
                                {
                                    $ssh = new Net_SSH2( $linux_info['lc_host'] );
                                    if( !$ssh->login( $linux_info['lc_user'], $linux_info['lc_password'] ) )
                                    {
                                        echo '<span style="color:red">NO</span>';
                                    } else
                                    {
                                        echo '<span style="color:green">OK</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td><?php echo $linux_info['lcg_name'] ?></td>
                            <td>
                                <a href="?action=linux_edit_detail&id=<?php echo $linux_info['lc_id'] ?>">修改</a>
                                <a onclick="return confirm('确定删除<?php echo $mysql_info['lc_host'] ?>?')" href="?action=linux_del&id=<?php echo $linux_info['lc_id'] ?>">删除</a>
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