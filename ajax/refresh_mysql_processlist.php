<?php
include_once( "../include/common.inc.php" );
$cls_data_mc = new cls_data('c_mysql_config');
$mysql_info = $cls_data_mc->execute( "select mc_id,mc_host,mc_user,mc_password,lc_id,lc_host,lc_user,lc_password from c_mysql_config inner join c_linux_config on mc_host=lc_host where mc_is_index_processlist=1 and mc_id={$mc_id} limit 1" );
$mysql_info = $mysql_info[0];

$result = array();

$ssh = new Net_SSH2( $mysql_info['lc_host'] );
if( !$ssh->login( $mysql_info['lc_user'], $mysql_info['lc_password'] ) )
{
    $result['ack'] = 0;
    $result['error_id'] = 1000;
    $result['msg'] = '登陆失败';
} else
{
    $cmd = "mysql -h {$mysql_info['mc_host']} -u{$mysql_info['mc_user']} -p{$mysql_info['mc_password']} -e 'show processlist'";
    $msg = $ssh->exec($cmd);
    $result['ack'] = 1;
    $result['msg'] = date('Y-m-d H:i:s') . '<br>' . $msg;
}
echo json_encode( $result );
