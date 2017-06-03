-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2017 at 12:50 PM
-- Server version: 5.6.25-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpcluster`
--

-- --------------------------------------------------------

--
-- Table structure for table `c_linux_config`
--

CREATE TABLE IF NOT EXISTS `c_linux_config` (
  `lc_id` int(11) NOT NULL AUTO_INCREMENT,
  `lc_host` varchar(50) NOT NULL,
  `lc_user` varchar(20) NOT NULL,
  `lc_password` varchar(30) NOT NULL,
  `lc_port` smallint(6) NOT NULL,
  `lc_add_time` int(11) NOT NULL,
  `lc_update_time` int(11) NOT NULL,
  PRIMARY KEY (`lc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `c_mysql_config`
--

CREATE TABLE IF NOT EXISTS `c_mysql_config` (
  `mc_id` int(11) NOT NULL AUTO_INCREMENT,
  `mc_host` varchar(30) NOT NULL,
  `mc_user` varchar(20) NOT NULL,
  `mc_password` varchar(30) NOT NULL,
  `mc_port` varchar(5) NOT NULL,
  `mc_add_time` int(11) NOT NULL,
  `mc_update_time` int(11) NOT NULL,
  `mc_is_index_processlist` tinyint(1) NOT NULL DEFAULT '0',
  `mc_refresh_processlist_sec` tinyint(4) NOT NULL,
  PRIMARY KEY (`mc_id`),
  UNIQUE KEY `uidx_host` (`mc_host`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `c_mysql_kill_detail`
--

CREATE TABLE IF NOT EXISTS `c_mysql_kill_detail` (
  `cmkd_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmkd_add_time` int(11) NOT NULL,
  `cmkd_sql` varchar(500) NOT NULL,
  `cmkd_kill_sec` int(11) NOT NULL,
  `cmkd_thread_id` int(11) NOT NULL,
  `cmkd_host` varchar(50) NOT NULL,
  PRIMARY KEY (`cmkd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
