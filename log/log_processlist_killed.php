<?php
include_once( "../include/common.inc.php" );
$page_title = '被killed的列表';
$cls_data_mkd = new cls_data('c_mysql_kill_detail');
$mkd_list = $cls_data_mkd->select_ex( array( 'order'=> 'cmkd_id desc' ) );
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
                <div class="well">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>mysql</th>
                            <th>Killed的秒数</th>
                            <th>thread id</th>
                            <th>添加时间</th>
                            <th>SQL</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach( $mkd_list as $mkd_info )
                            {
                        ?>
                        <tr>
                            <td><?php echo $mkd_info['cmkd_id'] ?></td>
                            <td><?php echo $mkd_info['cmkd_host'] ?></td>
                            <td><?php echo $mkd_info['cmkd_is_killed'] ? 'killed-' : 'unkilled-'; ?><?php echo $mkd_info['cmkd_kill_sec'] ?></td>
                            <td><?php echo $mkd_info['cmkd_thread_id'] ?></td>
                            <td><?php echo date('Y-m-d H:i:s', $mkd_info['cmkd_add_time']); ?></td>
                            <td><?php echo $mkd_info['cmkd_sql'] ?></td>
                        </tr>
                                <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once( '../footer.php' );
    ?>



    
  </body>
</html>