<?php
include_once( "../include/common.inc.php" );

$page_title = 'Linux分组';
$cls_data_lcg = new cls_data('c_linux_config_group');
$cls_data_lc = new cls_data('c_linux_config');
if( 'linux_add' == $action )
{
    if( empty( $group_name ) )
    {
        show_msg( '请写组名', 2 );
    }else
    {
        $info = array(
            'lcg_name'=> $group_name,
            'lcg_add_time'=> time(),
        );
        if( $cls_data_lcg->insert_ex( $info ) )
        {
        }else
        {
            show_msg( '添加失败' . $cls_data_lcg->get_error(), 2 );
        }
    }
}
if( 'linux_del' == $action )
{
    $group_info_detail = $cls_data_lc->delete_ex( "lcg_id={$id}" );
}
$linux_group_list = $cls_data_lcg->select_ex();
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
                        <input type="hidden" value="<?php echo $group_info_detail['lcg_id'] ?>" name="id">
                        分组名:<input name="group_name" value="<?php echo $group_info_detail['lcg_name'] ?>" class="input-small" type="text">
                        <button class="btn btn-primary"><i class="icon-plus"></i> <?php echo 'linux_edit_detail' == $action ? '修改' : '添加'; ?></button>
                    </form>
                    <form action="#" method="post">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>分组</th>
                            <th width="100">操作</th>
                            <th>拥有主机</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach( $linux_group_list as $group_info )
                            {
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" value="<?php echo $group_info['lcg_id'] ?>" name="lc_id[<?php echo $group_info['lcg_id'] ?>]">
                                <?php echo $group_info['lcg_id'] ?></td>
                            <td><?php echo $group_info['lcg_name'] ?></td>
                            <td><a target="_blank" href="linux_group_option.php?group_id=<?php echo $group_info['lcg_id']; ?>&type=mysql_option">操作mysql</a><br><a target="_blank" href="linux_group_option.php?group_id=<?php echo $group_info['lcg_id']; ?>&type=linux_option">操作linux</a></td>
                            <td style="word-break: break-all">
                                <?php
                                    $linux_list = $cls_data_lc->select_ex( array( 'where'=> "find_in_set({$group_info['lcg_id']},lc_group_id)" ) );
                                    foreach( $linux_list as $linux_info )
                                    {
                                        echo $linux_info['lc_name'] . '-';
                                    }
                                ?>
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