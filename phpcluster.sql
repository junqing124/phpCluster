SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `c_linux_config` (
  `lc_id` int(11) NOT NULL,
  `lc_host` varchar(50) NOT NULL,
  `lc_user` varchar(20) NOT NULL,
  `lc_password` varchar(30) NOT NULL,
  `lc_port` smallint(6) NOT NULL,
  `lc_add_time` int(11) NOT NULL,
  `lc_update_time` int(11) NOT NULL,
  `lc_group_id` smallint(3) NOT NULL,
  `lc_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `c_linux_config_group` (
  `lcg_id` int(11) NOT NULL,
  `lcg_name` varchar(30) NOT NULL,
  `lcg_add_time` int(11) NOT NULL,
  `lcg_add_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `c_mysql_config` (
  `mc_id` int(11) NOT NULL,
  `mc_host` varchar(30) NOT NULL,
  `mc_user` varchar(20) NOT NULL,
  `mc_password` varchar(30) NOT NULL,
  `mc_port` varchar(5) NOT NULL,
  `mc_add_time` int(11) NOT NULL,
  `mc_update_time` int(11) NOT NULL,
  `mc_is_index_processlist` tinyint(1) NOT NULL DEFAULT '0',
  `mc_refresh_processlist_sec` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `c_mysql_kill_detail` (
  `cmkd_id` int(11) NOT NULL,
  `cmkd_add_time` int(11) NOT NULL,
  `cmkd_sql` varchar(500) NOT NULL,
  `cmkd_kill_sec` int(11) NOT NULL,
  `cmkd_thread_id` int(11) NOT NULL,
  `cmkd_host` varchar(50) NOT NULL,
  `cmkd_is_killed` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `c_linux_config`
  ADD PRIMARY KEY (`lc_id`),
  ADD UNIQUE KEY `uidx_host` (`lc_host`);

ALTER TABLE `c_linux_config_group`
  ADD PRIMARY KEY (`lcg_id`),
  ADD UNIQUE KEY `uidx_name` (`lcg_name`);

ALTER TABLE `c_mysql_config`
  ADD PRIMARY KEY (`mc_id`),
  ADD UNIQUE KEY `uidx_host` (`mc_host`);

ALTER TABLE `c_mysql_kill_detail`
  ADD PRIMARY KEY (`cmkd_id`);


ALTER TABLE `c_linux_config`
  MODIFY `lc_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `c_linux_config_group`
  MODIFY `lcg_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `c_mysql_config`
  MODIFY `mc_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `c_mysql_kill_detail`
  MODIFY `cmkd_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `c_linux_config` CHANGE `lc_group_id` `lc_group_id` VARCHAR(100) NOT NULL;