-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2015 at 12:59 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `username` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `super` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `already_submitted`
--

CREATE TABLE IF NOT EXISTS `already_submitted` (
  `section_id` int(11) NOT NULL,
  `s_id_hashed` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `sub_id` int(11) NOT NULL,
  `q_id` int(11) NOT NULL,
  `answer` int(11) DEFAULT NULL,
  `comments` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `course_question`
--

CREATE TABLE IF NOT EXISTS `course_question` (
  `section_id` int(11) NOT NULL,
  `q_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE IF NOT EXISTS `instructor` (
  `inst_id_hashed` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `email` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
`q_id` int(11) NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `q_type` int(4) NOT NULL,
  `creator_type` varchar(128) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `instructor` varchar(128) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
`section_id` int(11) NOT NULL,
  `course_subject` varchar(4) NOT NULL,
  `course_num` int(11) NOT NULL,
  `course_section` int(4) NOT NULL,
  `term` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `instructor` varchar(128) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `eval_start` date NOT NULL,
  `eval_end` date NOT NULL,
  `student_count` int(11) NOT NULL DEFAULT '0',
  `modified` tinyint(1) DEFAULT NULL,
  `modified_date` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

CREATE TABLE IF NOT EXISTS `submission` (
`sub_id` int(11) NOT NULL,
  `s_id_hashed` varchar(128) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `submitted` tinyint(1) NOT NULL DEFAULT '0',
  `general_comment` varchar(4096) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=396 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
 ADD PRIMARY KEY (`username`);

--
-- Indexes for table `already_submitted`
--
ALTER TABLE `already_submitted`
 ADD PRIMARY KEY (`section_id`,`s_id_hashed`);

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
 ADD PRIMARY KEY (`sub_id`,`q_id`), ADD KEY `q_id` (`q_id`);

--
-- Indexes for table `course_question`
--
ALTER TABLE `course_question`
 ADD PRIMARY KEY (`section_id`,`q_id`), ADD KEY `q_id` (`q_id`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
 ADD PRIMARY KEY (`inst_id_hashed`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
 ADD PRIMARY KEY (`q_id`), ADD KEY `instructor` (`instructor`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
 ADD PRIMARY KEY (`section_id`), ADD KEY `instructor` (`instructor`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
 ADD PRIMARY KEY (`session_id`), ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `submission`
--
ALTER TABLE `submission`
 ADD PRIMARY KEY (`sub_id`), ADD UNIQUE KEY `s_id_hashed` (`s_id_hashed`,`section_id`), ADD KEY `section_id` (`section_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=282;
--
-- AUTO_INCREMENT for table `submission`
--
ALTER TABLE `submission`
MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=396;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `already_submitted`
--
ALTER TABLE `already_submitted`
ADD CONSTRAINT `already_submitted_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`sub_id`) REFERENCES `submission` (`sub_id`) ON DELETE CASCADE,
ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`q_id`) REFERENCES `question` (`q_id`);

--
-- Constraints for table `course_question`
--
ALTER TABLE `course_question`
ADD CONSTRAINT `course_question_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE,
ADD CONSTRAINT `course_question_ibfk_2` FOREIGN KEY (`q_id`) REFERENCES `question` (`q_id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`instructor`) REFERENCES `instructor` (`inst_id_hashed`);

--
-- Constraints for table `section`
--
ALTER TABLE `section`
ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`instructor`) REFERENCES `instructor` (`inst_id_hashed`);

--
-- Constraints for table `submission`
--
ALTER TABLE `submission`
ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
