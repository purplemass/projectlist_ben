-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 05, 2009 at 04:51 PM
-- Server version: 5.0.41
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `projectlist`
--
CREATE DATABASE `projectlist` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `projectlist`;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `CId` int(11) NOT NULL auto_increment,
  `CName` varchar(100) NOT NULL,
  `CAddress` varchar(256) NOT NULL,
  `CEmail` varchar(100) NOT NULL,
  `CPhone` varchar(20) NOT NULL,
  `CFax` varchar(20) NOT NULL,
  `CPerson` varchar(256) NOT NULL,
  PRIMARY KEY  (`CId`),
  UNIQUE KEY `CName` (`CName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` VALUES(47, 'Intel', '', '', '', '', '');
INSERT INTO `clients` VALUES(48, 'Jaguar X351', '', '', '', '', '');
INSERT INTO `clients` VALUES(44, 'Vattenfall', '', '', '', '', '');
INSERT INTO `clients` VALUES(45, 'Visit Scotland', '', '', '', '', '');
INSERT INTO `clients` VALUES(46, 'FOE', '', '', '', '', '');
INSERT INTO `clients` VALUES(42, 'One World', '', '', '', '', '');
INSERT INTO `clients` VALUES(43, 'Shell', '', '', '', '', '');
INSERT INTO `clients` VALUES(41, 'Holcim', '', '', '', '', '');
INSERT INTO `clients` VALUES(39, 'BBC Radio', '', '', '', '', '');
INSERT INTO `clients` VALUES(40, 'EADS', '', '', '', '', '');
INSERT INTO `clients` VALUES(36, 'AOL', '', '', '', '', '');
INSERT INTO `clients` VALUES(37, '', '', '', '', '', '');
INSERT INTO `clients` VALUES(38, 'Apollo Manchester', '', '', '', '', '');
INSERT INTO `clients` VALUES(33, 'Imagination Ltd (London)', '25 Store Street', 'info@inagination.com', '', '', '');
INSERT INTO `clients` VALUES(35, 'V&A Museum', 'Cromwell Road, London SW7 2RL', 'webmaster@vam.ac.uk', ' 44 (0)20 7942 2000', ' 44 (0)20 7942 2000', '');
INSERT INTO `clients` VALUES(49, 'Land Rover', '', '', '', '', '');
INSERT INTO `clients` VALUES(50, 'Otarian', '', '', '', '', '');
INSERT INTO `clients` VALUES(51, 'Which?', '', '', '', '', '');
INSERT INTO `clients` VALUES(52, 'Ford', '', '', '', '', '');
INSERT INTO `clients` VALUES(53, 'Candy & Candy', '', '', '', '', '');
INSERT INTO `clients` VALUES(54, 'Manchester City', '', '', '', '', '');
INSERT INTO `clients` VALUES(55, 'Royal Opera House', '', '', '', '', '');
INSERT INTO `clients` VALUES(56, 'Samsung', '', '', '', '', '');
INSERT INTO `clients` VALUES(57, 'Singapore IDE', '', '', '', '', '');
INSERT INTO `clients` VALUES(58, 'Stockholm City', '', '', '', '', '');
INSERT INTO `clients` VALUES(59, 'Imagination Group', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `HId` int(11) NOT NULL auto_increment,
  `HFName` varchar(50) NOT NULL,
  `HLName` varchar(50) NOT NULL,
  `HEmail` varchar(100) NOT NULL,
  PRIMARY KEY  (`HId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` VALUES(6, 'Benas', 'Brazdziunas', 'benas.brazdziunas@imagination.com');
INSERT INTO `person` VALUES(37, 'Dan', 'Diggins', 'dan.diggins@imagination.com');
INSERT INTO `person` VALUES(34, 'Barmak', 'Hatamian', 'barmak.hatamian@imagination.com');
INSERT INTO `person` VALUES(35, 'Andrew', 'Skinner', 'andrew.skinner@imagination.com');
INSERT INTO `person` VALUES(36, 'Babak', 'Hatamian', 'babak.hatamian@imagination.com');

-- --------------------------------------------------------

--
-- Table structure for table `pro_cli`
--

CREATE TABLE `pro_cli` (
  `PCProject` int(11) NOT NULL,
  `PCClient` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pro_cli`
--

INSERT INTO `pro_cli` VALUES(232, 42);
INSERT INTO `pro_cli` VALUES(237, 35);
INSERT INTO `pro_cli` VALUES(230, 40);
INSERT INTO `pro_cli` VALUES(231, 41);
INSERT INTO `pro_cli` VALUES(229, 39);
INSERT INTO `pro_cli` VALUES(228, 38);
INSERT INTO `pro_cli` VALUES(227, 36);
INSERT INTO `pro_cli` VALUES(234, 43);
INSERT INTO `pro_cli` VALUES(233, 42);
INSERT INTO `pro_cli` VALUES(236, 35);
INSERT INTO `pro_cli` VALUES(235, 44);
INSERT INTO `pro_cli` VALUES(0, 0);
INSERT INTO `pro_cli` VALUES(226, 33);

-- --------------------------------------------------------

--
-- Table structure for table `pro_per`
--

CREATE TABLE `pro_per` (
  `PPPerson` int(11) NOT NULL,
  `PPProject` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pro_per`
--

INSERT INTO `pro_per` VALUES(37, 233);
INSERT INTO `pro_per` VALUES(35, 237);
INSERT INTO `pro_per` VALUES(35, 229);
INSERT INTO `pro_per` VALUES(34, 234);
INSERT INTO `pro_per` VALUES(6, 237);
INSERT INTO `pro_per` VALUES(35, 230);
INSERT INTO `pro_per` VALUES(35, 231);
INSERT INTO `pro_per` VALUES(35, 228);
INSERT INTO `pro_per` VALUES(37, 232);
INSERT INTO `pro_per` VALUES(35, 227);
INSERT INTO `pro_per` VALUES(34, 236);
INSERT INTO `pro_per` VALUES(36, 235);
INSERT INTO `pro_per` VALUES(0, 0);
INSERT INTO `pro_per` VALUES(6, 226);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `PId` int(11) NOT NULL auto_increment,
  `PNumber` varchar(10) NOT NULL,
  `PTitle` varchar(100) NOT NULL,
  `PContent` varchar(255) default NULL,
  `PCreated` date NOT NULL,
  `PUser` int(11) NOT NULL,
  `PDueDate` date default NULL,
  `PEditPerson` int(11) NOT NULL,
  `PLastUpdate` datetime NOT NULL,
  `PStatus` tinyint(4) NOT NULL,
  `PAction` varchar(256) NOT NULL,
  PRIMARY KEY  (`PId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=239 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` VALUES(236, '2597/m', 'Baroque Microsite Phase 3', 'Phase 3 will be in June, simple site closure', '2009-04-29', 6, '2009-06-15', 6, '2009-04-29 11:18:31', 0, '236');
INSERT INTO `projects` VALUES(229, '2643/m', 'Pitch Presentation', '', '2009-04-29', 6, '2009-04-30', 6, '2009-04-29 10:57:35', 0, '229');
INSERT INTO `projects` VALUES(230, '2768/m', 'SLA Maintenance', '', '2009-04-29', 6, '2009-06-30', 6, '2009-04-29 11:11:17', 0, '230');
INSERT INTO `projects` VALUES(231, '2772/m', 'Project Nautilus Comms App', 'Confirm script, and tech approach with client', '2009-04-29', 6, '2009-05-15', 6, '2009-04-29 11:12:31', 0, '231');
INSERT INTO `projects` VALUES(232, '2724/m', 'Travel Library (Bookstations)', 'Issues with Italian wifi provider not set up for 1st May', '2009-04-29', 6, '2009-05-01', 6, '2009-04-29 11:13:28', 0, '232');
INSERT INTO `projects` VALUES(233, '2698/m', 'Travel Hub (Digital Dashboard)', 'MH/JH to work up creative, behind schedule for this\n\nWireframes to be complete and issued to US\n', '2009-04-29', 6, '2009-05-15', 6, '2009-04-29 11:14:21', 0, '233');
INSERT INTO `projects` VALUES(234, '2781/m', 'NEF Energy Toolkit', 'Kick-off meeting scheduled for 01/04', '2009-04-29', 6, '2009-04-30', 6, '2009-04-29 11:15:24', 0, '234');
INSERT INTO `projects` VALUES(235, 'S188/m', 'Energy & Climate Day', 'Finland exhibition starts n/ week', '2009-04-29', 6, '2009-04-30', 6, '2009-04-29 11:16:40', 0, '235');
INSERT INTO `projects` VALUES(226, 'TBC', 'Project List', 'Create web page to manage the project`s', '2009-04-28', 6, '2009-05-05', 6, '2009-04-29 11:19:43', 1, '226');
INSERT INTO `projects` VALUES(227, 'TBC', 'Digital activation pitch', '', '2009-04-29', 6, '2009-05-15', 6, '2009-04-29 10:55:55', 0, '227');
INSERT INTO `projects` VALUES(228, '2287/m', 'Premier League Pavillion Tour Development', '', '2009-04-29', 6, '2009-05-15', 6, '2009-04-29 10:56:49', 0, '228');
INSERT INTO `projects` VALUES(237, '2559/m', 'Creative Spaces Promotion Phase 2', 'Minimal housekeeping updates being made', '2009-04-29', 6, '2009-04-30', 36, '2009-05-05 16:28:00', 1, '237');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UId` int(11) NOT NULL auto_increment,
  `UName` varchar(50) NOT NULL,
  `UPass` varchar(50) NOT NULL,
  `UPerson` int(11) NOT NULL,
  `UCreated` date default NULL,
  `ULastLogin` datetime default NULL,
  PRIMARY KEY  (`UId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` VALUES(40, 'dan', '9180b4da3f0c7e80975fad685f7f134e', 37, '2009-04-29', '2009-04-29 10:53:14');
INSERT INTO `user` VALUES(39, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', 36, '2009-04-28', '2009-05-05 16:20:41');
INSERT INTO `user` VALUES(9, 'ben', '81dc9bdb52d04dc20036dbd8313ed055', 6, '2009-02-25', '2009-05-05 16:35:46');
INSERT INTO `user` VALUES(38, 'andrew', 'd914e3ecf6cc481114a3f534a5faf90b', 35, '2009-04-28', '2009-04-28 11:22:18');
INSERT INTO `user` VALUES(37, 'max', '2ffe4e77325d9a7152f7086ea7aa5114', 34, '2009-04-28', '2009-04-28 11:23:34');
