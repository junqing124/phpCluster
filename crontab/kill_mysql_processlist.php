<?php
@include_once( "/home/www/phpCluster/include/common.inc.php" );
@include_once( "../include/common.inc.php" );
$cls_data_mc = new cls_data('c_mysql_config');
$mc_id = $argv[1];
$kill_sec = $argv[2];
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
    $cmd = "mysql -h {$mysql_info['mc_host']} -u{$mysql_info['mc_user']} -p{$mysql_info['mc_password']} -e 'show full processlist'";
    $msg = $ssh->exec($cmd);
    $list = explode( "\n", $msg );
    foreach( $list as $list_str )
    {
        $tmp = explode( "\t", $list_str );
        $cur_time = intval( $tmp[5] );
        $cur_id = $tmp[0];
        $sql = $tmp[7];
        if( $cur_time >= $kill_sec )
        {
            $cmd = "mysql -h {$mysql_info['mc_host']} -u{$mysql_info['mc_user']} -p{$mysql_info['mc_password']} -e 'kill {$cur_id}'";
            $info_detail = array(
                'cmkd_add_time'=> time(),
                'cmkd_sql'=> $sql,
                'cmkd_kill_sec'=> $kill_sec,
                'cmkd_thread_id'=> $cur_id,
                'cmkd_host'=> $mysql_info['mc_host'],
            );
            $cls_data_mkd = new cls_data('c_mysql_kill_detail');
            $cls_data_mkd->insert_ex( $info_detail );
            $msg = $ssh->exec($cmd);
        }
    }
}
