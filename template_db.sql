-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2014 at 03:42 PM
-- Server version: 5.5.35
-- PHP Version: 5.5.10-1+deb.sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `template_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `counties`
--

CREATE TABLE IF NOT EXISTS `counties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fullrecords`
--

CREATE TABLE IF NOT EXISTS `fullrecords` (
  `company` varchar(100) NOT NULL,
  `lob` varchar(100) NOT NULL,
  `provId` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `practicename` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `suffix` varchar(100) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `suite` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(3) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `zip4` varchar(50) NOT NULL,
  `county` varchar(100) NOT NULL,
  `servicearea` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `gender` varchar(2) NOT NULL,
  `handicap` varchar(2) NOT NULL,
  `acceptingnew` varchar(2) NOT NULL,
  `acceptsmedicare` varchar(2) NOT NULL,
  `acceptsmedicaid` varchar(2) NOT NULL,
  `hospaffiliations` varchar(100) NOT NULL,
  `languages` text NOT NULL,
  `officehours` varchar(100) NOT NULL,
  `tty` varchar(30) NOT NULL,
  `specialexperince` text NOT NULL,
  `adacapabilities` text NOT NULL,
  `certifications` varchar(150) NOT NULL,
  `culturalcompetancy` varchar(2) NOT NULL,
  `publictransavailable` varchar(2) NOT NULL,
  `customfield1desc` varchar(30) NOT NULL,
  `customfield1ind` text NOT NULL,
  `customfield2desc` varchar(30) NOT NULL,
  `customfield2ind` text NOT NULL,
  `customfield3desc` varchar(30) NOT NULL,
  `customfield3ind` text NOT NULL,
  `latitude_str` varchar(10) NOT NULL,
  `longitude_str` varchar(10) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=262141 ;

-- --------------------------------------------------------

--
-- Table structure for table `insurances`
--

CREATE TABLE IF NOT EXISTS `insurances` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `address3` varchar(255) DEFAULT NULL,
  `address4` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `county` varchar(100) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `zipcode2` varchar(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `ephone` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `wheelchair_accessible` int(1) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `cstm_desc_1` varchar(100) NOT NULL,
  `cstm_ind_1` varchar(100) NOT NULL,
  `cstm_desc_2` varchar(100) NOT NULL,
  `cstm_ind_2` varchar(100) NOT NULL,
  `cstm_desc_3` varchar(100) NOT NULL,
  `cstm_ind_3` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `locations_counties`
--

CREATE TABLE IF NOT EXISTS `locations_counties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `countie_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations_insurances`
--

CREATE TABLE IF NOT EXISTS `locations_insurances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `insurance_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations_languages`
--

CREATE TABLE IF NOT EXISTS `locations_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations_networks`
--

CREATE TABLE IF NOT EXISTS `locations_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `locations_specialties`
--

CREATE TABLE IF NOT EXISTS `locations_specialties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `specialtie_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `networks`
--

CREATE TABLE IF NOT EXISTS `networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE IF NOT EXISTS `providers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `suffix` varchar(11) NOT NULL,
  `g` varchar(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `biography` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `board_certified` int(2) NOT NULL,
  `prov_id` varchar(255) NOT NULL,
  `acpt_new` int(11) NOT NULL,
  `acpt_medicare` int(11) NOT NULL,
  `acpt_medicaid` int(11) NOT NULL,
  `hours` varchar(100) NOT NULL,
  `hosp_affl` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `providers_counties`
--

CREATE TABLE IF NOT EXISTS `providers_counties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `countie_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `providers_insurances`
--

CREATE TABLE IF NOT EXISTS `providers_insurances` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) unsigned NOT NULL,
  `insurance_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `providers_languages`
--

CREATE TABLE IF NOT EXISTS `providers_languages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `providers_locations`
--

CREATE TABLE IF NOT EXISTS `providers_locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) unsigned NOT NULL,
  `location_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `providers_networks`
--

CREATE TABLE IF NOT EXISTS `providers_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `providers_specialties`
--

CREATE TABLE IF NOT EXISTS `providers_specialties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) unsigned NOT NULL,
  `specialtie_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE IF NOT EXISTS `specialties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11)  NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `providertypes`
--

CREATE TABLE IF NOT EXISTS `providertypes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lob` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `temptables`
--

CREATE TABLE IF NOT EXISTS `temptables` (
  `SortName` varchar(255) NOT NULL,
  `LAST_NAME` varchar(255) NOT NULL,
  `FIRST_NAME` varchar(255) NOT NULL,
  `DEGREE` varchar(255) NOT NULL,
  `SPECIALTY` varchar(255) NOT NULL,
  `SPECIALTY_CODE` varchar(255) NOT NULL,
  `OFFICE_NAME` varchar(255) NOT NULL,
  `ADDRESS` varchar(255) NOT NULL,
  `CITY` varchar(255) NOT NULL,
  `STATE` varchar(255) NOT NULL,
  `ZIP` varchar(255) NOT NULL,
  `COUNTY` varchar(255) NOT NULL,
  `PHONE` varchar(255) NOT NULL,
  `NPI` varchar(255) NOT NULL,
  `PPA` varchar(255) NOT NULL,
  `PROVID` varchar(255) NOT NULL,
  `ProviderType` varchar(255) NOT NULL,
  `Section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE fullrecords AUTO_INCREMENT=1;
ALTER TABLE networks AUTO_INCREMENT=1;
ALTER TABLE specialties AUTO_INCREMENT=1;
-- ALTER TABLE languages AUTO_INCREMENT=1;
ALTER TABLE providers AUTO_INCREMENT=1;
ALTER TABLE locations AUTO_INCREMENT=1;
ALTER TABLE providertypes AUTO_INCREMENT=1;
ALTER TABLE counties AUTO_INCREMENT=1;
ALTER TABLE insurances AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;