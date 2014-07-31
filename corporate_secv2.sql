-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2014 at 09:59 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `corporate_secv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `allot_director_fee`
--

CREATE TABLE IF NOT EXISTS `allot_director_fee` (
  `document_id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `allot_director_fee`
--

INSERT INTO `allot_director_fee` (`document_id`, `description`) VALUES
(235, ''),
(236, '');

-- --------------------------------------------------------

--
-- Table structure for table `appoint_auditors_secretaries`
--

CREATE TABLE IF NOT EXISTS `appoint_auditors_secretaries` (
  `document_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `secretary_id` int(11) NOT NULL,
  `auditor_name` varchar(100) NOT NULL,
  `auditor_address` varchar(100) NOT NULL,
  `NameDS` varchar(100) NOT NULL,
  `LO_name` varchar(100) NOT NULL,
  `LO_address1` varchar(100) NOT NULL,
  `LO_address2` varchar(100) NOT NULL,
  `LO_telno` varchar(20) NOT NULL,
  `LO_fax` varchar(20) NOT NULL,
  PRIMARY KEY (`document_id`),
  KEY `aus_fk2` (`event_id`),
  KEY `aus_fk3` (`secretary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `appoint_resign_directors`
--

CREATE TABLE IF NOT EXISTS `appoint_resign_directors` (
  `document_id` int(11) NOT NULL,
  `director_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `attn` varchar(50) NOT NULL,
  `NameSDs` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`),
  KEY `appoint_resign_directors_ibfk_1` (`director_id`),
  KEY `appoint_resign_directors_ibfk_2` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `appoint_resign_secretaries`
--

CREATE TABLE IF NOT EXISTS `appoint_resign_secretaries` (
  `document_id` int(11) NOT NULL,
  `secretary_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `attn` varchar(20) NOT NULL,
  `NameDS` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`),
  KEY `appoint_resign_secretaries_ibfk_2` (`secretary_id`),
  KEY `appoint_resign_secretaries_ibfk_1` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `auditors`
--

CREATE TABLE IF NOT EXISTS `auditors` (
  `id` int(11) NOT NULL,
  `Mode` varchar(50) DEFAULT NULL,
  `OtherOccupation` varchar(100) DEFAULT NULL,
  `addressLine1` varchar(100) DEFAULT NULL,
  `addressLine2` varchar(100) DEFAULT NULL,
  `addressLine3` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auditors`
--

INSERT INTO `auditors` (`id`, `Mode`, `OtherOccupation`, `addressLine1`, `addressLine2`, `addressLine3`) VALUES
(590, 'appointed', 'IT', NULL, NULL, NULL),
(598, 'appointed', 'IT', NULL, NULL, NULL),
(600, 'appointed', 'sdsdfsdf', '3 LOP', '4 jskfd', 'sdfsdf');

-- --------------------------------------------------------

--
-- Table structure for table `change_bank_signator_uob`
--

CREATE TABLE IF NOT EXISTS `change_bank_signator_uob` (
  `document_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `director_id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`),
  KEY `cbsu_fk2` (`event_id`),
  KEY `cbsu_fk3` (`director_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `change_company_names`
--

CREATE TABLE IF NOT EXISTS `change_company_names` (
  `document_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `directors` varchar(100) NOT NULL,
  `shareholders` varchar(100) NOT NULL,
  `meeting_address1` varchar(100) NOT NULL,
  `meeting_address2` varchar(100) NOT NULL,
  `chairman` varchar(100) NOT NULL,
  `nameDS` varchar(100) NOT NULL,
  `new_company` varchar(100) NOT NULL,
  `old_company` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`),
  KEY `ccn_fk2` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `change_financial_years`
--

CREATE TABLE IF NOT EXISTS `change_financial_years` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `change_maa`
--

CREATE TABLE IF NOT EXISTS `change_maa` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `change_passport`
--

CREATE TABLE IF NOT EXISTS `change_passport` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `change_registered_address`
--

CREATE TABLE IF NOT EXISTS `change_registered_address` (
  `document_id` int(11) NOT NULL,
  `old_address` varchar(100) NOT NULL,
  `new_address` varchar(100) NOT NULL,
  `nameDS` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `closure_bank_acc`
--

CREATE TABLE IF NOT EXISTS `closure_bank_acc` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `company_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `register_number` varchar(9) NOT NULL,
  `address_1` varchar(100) NOT NULL,
  `address_2` varchar(50) NOT NULL,
  `telephone` varchar(8) NOT NULL,
  `fax` varchar(8) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `FinancialYear` varchar(20) NOT NULL,
  `PrincipalActivity1` varchar(100) DEFAULT NULL,
  `P1Description` varchar(200) DEFAULT NULL,
  `PrincipalActivity2` varchar(100) DEFAULT NULL,
  `P2Description` varchar(200) DEFAULT NULL,
  `LOName` varchar(200) DEFAULT NULL,
  `LOAddressline1` varchar(100) DEFAULT NULL,
  `LOAddressline2` varchar(100) DEFAULT NULL,
  `LOAcNo` varchar(100) DEFAULT NULL,
  `LOTelNo` varchar(100) DEFAULT NULL,
  `LOTelFax` varchar(100) DEFAULT NULL,
  `Currency` varchar(100) DEFAULT NULL,
  `NumberOfShares` double DEFAULT NULL,
  `NominalAmountOfEachShare` double DEFAULT NULL,
  `AmountPaid` double DEFAULT NULL,
  `Due&Payable` double DEFAULT NULL,
  `AmountPremiumPaid` double DEFAULT NULL,
  `AuthorizedShareCapital` double DEFAULT NULL,
  `IssuedShareCapital` double DEFAULT NULL,
  `PaidupShareCapital` double DEFAULT NULL,
  `IssuedOrdinary` double DEFAULT NULL,
  `PaidUpOrdinary` double DEFAULT NULL,
  `unique_key` varchar(100) NOT NULL,
  `suscriberName` varchar(100) DEFAULT NULL,
  `suscriberShares` double DEFAULT NULL,
  `Approved` tinyint(4) NOT NULL,
  `Date_Of_Inc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=123 ;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `name`, `register_number`, `address_1`, `address_2`, `telephone`, `fax`, `created_at`, `updated_at`, `FinancialYear`, `PrincipalActivity1`, `P1Description`, `PrincipalActivity2`, `P2Description`, `LOName`, `LOAddressline1`, `LOAddressline2`, `LOAcNo`, `LOTelNo`, `LOTelFax`, `Currency`, `NumberOfShares`, `NominalAmountOfEachShare`, `AmountPaid`, `Due&Payable`, `AmountPremiumPaid`, `AuthorizedShareCapital`, `IssuedShareCapital`, `PaidupShareCapital`, `IssuedOrdinary`, `PaidUpOrdinary`, `unique_key`, `suscriberName`, `suscriberShares`, `Approved`, `Date_Of_Inc`) VALUES
(121, 'DREAMSMART PTE LTD', '223345', 'ABC ROAD 33 #01-23 ', 'SINGAPORE 1234567', '', '', '2014-07-18 16:55:14', '0000-00-00 00:00:00', '', 'PRODUCTION OF BOOKS', 'BOOKS FOR PRE-SCHOOL', 'PRODUCTION OF STATIONARIES', 'INCLUDING ARTS STATIONARIES AND SCHOOL STATION', 'XYZ PTE LTD', '77 XYZ ROAD', '#02-777 SINGAPORE 070707', '5167-8923', '67770707', '67771212', 'UNITED STATES DOLLARS', 100, 1, 100, NULL, NULL, 100, 100, 100, 100, 100, '730627d21a7e0bcef588575d03fe9a10efaffab4', 'MR SUBSCRIBER', NULL, 1, NULL),
(122, 'DREAMSMART 1  PTE LTD', '', 'ABC ROAD 123', '#01-23 SINGAPORE 1234567', '', '', '2014-07-23 16:32:24', '0000-00-00 00:00:00', '', 'PRODUCTION OF BOOKS', 'BOOKS FOR PRE-SCHOOL', 'PRODUCTION OF STATIONARIES', 'INCLUDING ARTS STATIONARIES AND SCHOOL STATION', 'XYZ PTE LTD', '77 XYZ ROAD', '#02-777 SINGAPORE 070707', '5167-8923', '67770707', '67771212', 'UNITED STATES DOLLARS', 100, 1, 100, NULL, NULL, 100, 100, 100, 100, 100, '14822198031ccc0846c1006f2c0132d3e82b0268', 'MR SUBSCRIBER', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `directors`
--

CREATE TABLE IF NOT EXISTS `directors` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Mode` varchar(50) DEFAULT NULL,
  `primary_address` int(11) DEFAULT NULL,
  `addressline1_Singapore` varchar(100) DEFAULT NULL,
  `addressline2_Singapore` varchar(100) DEFAULT NULL,
  `addressline3_Singapore` varchar(100) DEFAULT NULL,
  `addressline1_OverSea` varchar(100) DEFAULT NULL,
  `addressline2_OverSea` varchar(100) DEFAULT NULL,
  `addressline3_OverSea` varchar(100) DEFAULT NULL,
  `addressline1_Other` varchar(100) DEFAULT NULL,
  `addressline2_Other` varchar(100) DEFAULT NULL,
  `addressline3_Other` varchar(100) DEFAULT NULL,
  `CertificateNo` int(11) DEFAULT NULL,
  `NationalityatBirth` varchar(100) DEFAULT NULL,
  `Occupation` varchar(100) DEFAULT NULL,
  `NumberofShares` int(11) DEFAULT NULL,
  `NumberofSharesInwords` varchar(100) DEFAULT NULL,
  `DateofBirth` varchar(100) DEFAULT NULL,
  `ClassofShares` varchar(100) DEFAULT NULL,
  `Currency` varchar(100) DEFAULT NULL,
  `Placeofbirth` varchar(100) DEFAULT NULL,
  `Nricdateofissue` varchar(100) DEFAULT NULL,
  `nricplaceofissue` varchar(100) DEFAULT NULL,
  `passportno` varchar(100) DEFAULT NULL,
  `passportdateofissue` varchar(100) DEFAULT NULL,
  `passportplaceofissue` varchar(100) DEFAULT NULL,
  `NatureOfContract` varchar(100) DEFAULT NULL,
  `Remarks` varchar(100) DEFAULT NULL,
  `ConsentToActAsDirector` varchar(100) DEFAULT NULL,
  `FormerName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=594 ;

--
-- Dumping data for table `directors`
--

INSERT INTO `directors` (`id`, `Mode`, `primary_address`, `addressline1_Singapore`, `addressline2_Singapore`, `addressline3_Singapore`, `addressline1_OverSea`, `addressline2_OverSea`, `addressline3_OverSea`, `addressline1_Other`, `addressline2_Other`, `addressline3_Other`, `CertificateNo`, `NationalityatBirth`, `Occupation`, `NumberofShares`, `NumberofSharesInwords`, `DateofBirth`, `ClassofShares`, `Currency`, `Placeofbirth`, `Nricdateofissue`, `nricplaceofissue`, `passportno`, `passportdateofissue`, `passportplaceofissue`, `NatureOfContract`, `Remarks`, `ConsentToActAsDirector`, `FormerName`) VALUES
(583, 'appointed', 1, 'DIRECTORS ROAD 1 ', 'STREET D1', '#01-01 SINGAPORE 010101', '', '', '', '', '', '', 1, 'SINGAPOREAN', 'DIRECTOR', 40, 'Forty', '01-01-1981', 'Ordinary', 'UNITED STATES DOLLARS', 'SINGAPORE', '01-01-2001', 'SINGAPORE', '', '', '', '', '', '', ''),
(584, 'appointed', 1, 'DIRECTORS ROAD 2', 'STREET D2', '#02-02 MALAYSIA 020202', '', '', '', '', '', '', 2, 'MALAYSIAN', 'DIRECTOR', 30, 'Thirty', '02-02-1982', 'Ordinary', 'UNITED STATES DOLLARS', 'SINGAPORE', '02-02-2002', 'MALAYSIA', '', '', '', '', '', '', ''),
(585, 'appointed', 1, 'DIRECTORS ROAD 3', 'STREET D3', '#03-03 SINGAPORE 030303', '', '', '', '', '', '', 3, 'SINGAPOREAN', 'DIRECTOR', 30, 'Thirty', '03-03-1983', 'Ordinary', 'UNITED STATES DOLLARS', 'MALAYSIA', '03-03-2003', 'SINGAPORE', '', '', '', '', '', '', ''),
(591, 'appointed', 1, 'DIRECTORS ROAD 1 ', 'STREET D1', '#01-01 SINGAPORE 010101', '', '', '', '', '', '', 1, 'SINGAPOREAN', 'DIRECTOR', 40, 'Forty', '01-01-1981', 'Ordinary', 'UNITED STATES DOLLARS', 'SINGAPORE', '01-01-2001', 'SINGAPORE', '', '', '', '', '', '', ''),
(592, 'appointed', 1, 'DIRECTORS ROAD 2', 'STREET D2', '#02-02 MALAYSIA 020202', '', '', '', '', '', '', 2, 'MALAYSIAN', 'DIRECTOR', 30, 'Thirty', '02-02-1982', 'Ordinary', 'UNITED STATES DOLLARS', 'SINGAPORE', '02-02-2002', 'MALAYSIA', '', '', '', '', '', '', ''),
(593, 'appointed', 1, 'DIRECTORS ROAD 3', 'STREET D3', '#03-03 SINGAPORE 030303', '', '', '', '', '', '', 3, 'SINGAPOREAN', 'DIRECTOR', 30, 'Thirty', '03-03-1983', 'Ordinary', 'UNITED STATES DOLLARS', 'MALAYSIA', '03-03-2003', 'SINGAPORE', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `company_id` int(10) NOT NULL,
  `function_id` int(11) NOT NULL,
  `unique_key` varchar(100) NOT NULL,
  `before` varchar(100) NOT NULL,
  `after` varchar(100) NOT NULL,
  `before_time` datetime NOT NULL,
  `after_time` datetime NOT NULL,
  `acra_before` varchar(50) NOT NULL,
  `acra_after` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_fk1` (`company_id`),
  KEY `documents_fk2` (`function_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=247 ;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `company_id`, `function_id`, `unique_key`, `before`, `after`, `before_time`, `after_time`, `acra_before`, `acra_after`, `status`, `created_at`) VALUES
(214, 121, 13, 'd1278b8f841c6dd00532bd9e52147398cde72045', '', '', '2014-07-23 15:39:38', '2014-07-23 15:39:29', 'KLOPPDFPDF', 'KLOPPDFPDF', 'Available', '2014-07-23 15:02:42'),
(215, 122, 10, '0751e521ccc8d602303bc036d54a758609dbe1b4', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', 'Available', '2014-07-23 16:32:25'),
(216, 121, 14, 'cc10e014a6e9ef7b3a94a12a9662f9b66d62bc09', '', '', '2014-07-24 12:54:56', '2014-07-24 12:55:15', 'AJ907P', 'AJ907P', 'Available', '2014-07-24 12:34:16'),
(229, 121, 16, 'fb73aba77d9f9cbab06a4c8833434f721ece62f5', '', '', '2014-07-27 14:50:23', '2014-07-27 14:50:31', 'AJ907P', 'AJ907P', 'Available', '2014-07-27 14:31:21'),
(230, 121, 16, '4ca151ffe01ce8ef2acdaf3126568d36b2191159', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', 'Available', '2014-07-27 14:54:39'),
(235, 121, 17, '0ba47e3785a1912ff7beaad60af343357ed21ac6', '', '', '2014-07-28 14:13:15', '2014-07-28 14:13:39', '45678', '45678', 'Available', '2014-07-28 14:08:20'),
(236, 121, 17, '3ee3aff8910fb410d09f7c1f6f995c5a8bb04728', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', 'Available', '2014-07-28 17:42:40'),
(241, 121, 18, '1df9ec6905c09fc353d376b0e417f60d4b44fd2c', '', '', '2014-07-28 23:53:35', '0000-00-00 00:00:00', '45678', '', 'deleted', '2014-07-28 23:52:56'),
(246, 121, 20, '597179f74f404b15b00cf1c6840c7d8199297f7c', '', '', '2014-07-31 14:56:30', '2014-07-31 14:58:16', '45678', '45678', 'deleted', '2014-07-31 12:58:56');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `function_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `description` varchar(100) NOT NULL,
  `mode` varchar(20) NOT NULL,
  `unique_hash` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `events_fk2` (`company_id`),
  KEY `events_fk3` (`user_id`),
  KEY `events_fk1` (`function_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=238 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `function_id`, `company_id`, `user_id`, `created_time`, `description`, `mode`, `unique_hash`) VALUES
(157, 10, 121, 1, '2014-07-22 15:20:10', 'Delete before-submission documents Incorporation Document', '', ''),
(158, 10, 121, 1, '2014-07-22 15:20:16', 'Upload before-submission Incorporation Document', '', ''),
(159, 10, 121, 1, '2014-07-22 15:20:18', 'Download before submission documents Incorporation Document', '', ''),
(160, 10, 121, 1, '2014-07-22 15:20:20', 'Delete before-submission documents Incorporation Document', '', ''),
(161, 10, 121, 1, '2014-07-22 15:20:29', 'Upload after-submission Incorporation Document', '', ''),
(162, 10, 121, 1, '2014-07-22 15:20:31', ' Download after submission documentsIncorporation Document', '', ''),
(163, 10, 121, 1, '2014-07-22 15:20:32', 'Delete after-submission documents Incorporation Document', '', ''),
(164, 13, 121, 1, '2014-07-23 15:38:06', 'Upload after-submission documents[Function: SalesAssetBusiness]', '', ''),
(165, 13, 121, 1, '2014-07-23 15:38:10', ' Download after submission documentsSalesAssetBusiness', '', ''),
(166, 13, 121, 1, '2014-07-23 15:38:12', 'Delete after-submission documents ChangeRegisteredAddress', '', ''),
(167, 13, 121, 1, '2014-07-23 15:39:15', ' Download after submission documentsSalesAssetBusiness', '', ''),
(168, 13, 121, 1, '2014-07-23 15:39:29', 'Upload after-submission documents[Function: SalesAssetBusiness]', '', ''),
(169, 13, 121, 1, '2014-07-23 15:39:31', ' Download after submission documentsSalesAssetBusiness', '', ''),
(170, 13, 121, 1, '2014-07-23 15:39:33', 'Delete after-submission documents SalesAssetBusiness', '', ''),
(171, 13, 121, 1, '2014-07-23 15:39:38', 'Upload before-submission documents[Function: SalesAssetBusiness]', '', ''),
(172, 13, 121, 1, '2014-07-23 15:39:39', 'Download before submission documents SalesAssetBusiness', '', ''),
(173, 13, 121, 1, '2014-07-23 15:39:41', 'Delete before-submission documents SalesAssetBusiness', '', ''),
(174, 13, 121, 1, '2014-07-23 15:39:42', 'Delete  set of documents fromSalesAssetBusiness', '', ''),
(175, 14, 121, 1, '2014-07-24 12:54:56', 'Upload before-submission documents[Function: PropertyDisposal]', '', ''),
(176, 14, 121, 1, '2014-07-24 12:54:59', 'Download before submission documents PropertyDisposal', '', ''),
(177, 14, 121, 1, '2014-07-24 12:55:01', 'Delete before-submission documents PropertyDisposal', '', ''),
(178, 14, 121, 1, '2014-07-24 12:55:15', 'Upload after-submission documents[Function: PropertyDisposal]', '', ''),
(179, 14, 121, 1, '2014-07-24 12:55:16', ' Download after submission documentsPropertyDisposal', '', ''),
(180, 14, 121, 1, '2014-07-24 12:55:18', 'Delete after-submission documents PropertyDisposal', '', ''),
(181, 14, 121, 1, '2014-07-24 12:55:19', 'Delete  set of documents fromPropertyDisposal', '', ''),
(182, 15, 121, 1, '2014-07-24 18:22:44', 'Upload before-submission documents[Function: ResignAuditor]', '', ''),
(183, 15, 121, 1, '2014-07-24 18:25:09', 'Download before submission documents ResignAuditor', '', ''),
(184, 15, 121, 1, '2014-07-24 18:25:12', 'Delete before-submission documents ResignAuditor', '', ''),
(185, 15, 121, 1, '2014-07-24 18:25:17', 'Upload after-submission documents[Function: ResignAuditor]', '', ''),
(186, 15, 121, 1, '2014-07-24 18:25:18', ' Download after submission documentsResignAuditor', '', ''),
(187, 15, 121, 1, '2014-07-24 18:25:20', 'Delete after-submission documents ResignAuditor', '', ''),
(188, 15, 121, 1, '2014-07-24 18:25:21', 'Delete  set of documents fromResignAuditor', '', ''),
(189, 16, 121, 1, '2014-07-27 14:50:23', 'Upload before-submission documents[Function: NormalStruckOff]', '', ''),
(190, 16, 121, 1, '2014-07-27 14:50:31', 'Upload after-submission documents[Function: NormalStruckOff]', '', ''),
(191, 16, 121, 1, '2014-07-27 14:50:33', 'Download before submission documents NormalStruckOff', '', ''),
(192, 16, 121, 1, '2014-07-27 14:50:34', ' Download after submission documentsNormalStruckOff', '', ''),
(193, 16, 121, 1, '2014-07-27 14:50:37', 'Delete before-submission documents NormalStruckOff', '', ''),
(194, 16, 121, 1, '2014-07-27 14:50:39', 'Delete after-submission documents NormalStruckOff', '', ''),
(195, 16, 121, 1, '2014-07-27 14:50:42', 'Delete  set of documents fromNormalStruckOff', '', ''),
(196, 17, 121, 1, '2014-07-28 14:12:44', 'Upload before-submission documents[Function: AllotDirectorFee]', '', ''),
(197, 17, 121, 1, '2014-07-28 14:12:45', 'Download before submission documents AllotDirectorFee', '', ''),
(198, 17, 121, 1, '2014-07-28 14:12:59', 'Delete before-submission documents AllotDirectorFee', '', ''),
(199, 17, 121, 1, '2014-07-28 14:13:15', 'Upload before-submission documents[Function: AllotDirectorFee]', '', ''),
(200, 17, 121, 1, '2014-07-28 14:13:16', 'Download before submission documents AllotDirectorFee', '', ''),
(201, 17, 121, 1, '2014-07-28 14:13:25', 'Delete before-submission documents AllotDirectorFee', '', ''),
(202, 17, 121, 1, '2014-07-28 14:13:39', 'Upload after-submission documents[Function: AllotDirectorFee]', '', ''),
(203, 17, 121, 1, '2014-07-28 14:13:40', ' Download after submission documentsAllotDirectorFee', '', ''),
(204, 17, 121, 1, '2014-07-28 14:13:42', 'Delete after-submission documents AllotDirectorFee', '', ''),
(205, 17, 121, 1, '2014-07-28 14:13:44', 'Delete  set of documents fromAllotDirectorFee', '', ''),
(206, 18, 121, 1, '2014-07-28 22:55:20', 'Upload before-submission documents[Function: FirstFinalDividend]', '', ''),
(207, 18, 121, 1, '2014-07-28 22:55:22', 'Download before submission documents FirstFinalDividend', '', ''),
(208, 18, 121, 1, '2014-07-28 22:55:23', 'Delete before-submission documents FirstFinalDividend', '', ''),
(209, 18, 121, 1, '2014-07-28 22:55:31', 'Upload after-submission documents[Function: FirstFinalDividend]', '', ''),
(210, 18, 121, 1, '2014-07-28 22:55:32', ' Download after submission documentsFirstFinalDividend', '', ''),
(211, 18, 121, 1, '2014-07-28 22:55:33', 'Delete after-submission documents FirstFinalDividend', '', ''),
(212, 18, 121, 1, '2014-07-28 22:55:35', 'Delete  set of documents fromFirstFinalDividend', '', ''),
(213, 18, 121, 1, '2014-07-28 22:55:40', 'Delete  set of documents fromFirstFinalDividend', '', ''),
(214, 18, 121, 1, '2014-07-28 23:53:35', 'Upload before-submission documents[Function: FirstFinalDividend]', '', ''),
(215, 18, 121, 1, '2014-07-28 23:53:36', 'Download before submission documents FirstFinalDividend', '', ''),
(216, 18, 121, 1, '2014-07-28 23:53:37', 'Delete before-submission documents FirstFinalDividend', '', ''),
(217, 18, 121, 1, '2014-07-28 23:53:40', 'Delete  set of documents fromFirstFinalDividend', '', ''),
(218, 19, 121, 1, '2014-07-29 18:40:11', 'Upload before-submission documents[Function: IncreaseOfShare]', '', ''),
(219, 19, 121, 1, '2014-07-29 18:40:12', 'Download before submission documents IncreaseOfShare', '', ''),
(220, 19, 121, 1, '2014-07-29 18:40:15', 'Delete before-submission documents IncreaseOfShare', '', ''),
(221, 19, 121, 1, '2014-07-29 18:40:25', 'Upload after-submission documents[Function: IncreaseOfShare]', '', ''),
(222, 19, 121, 1, '2014-07-29 18:40:27', ' Download after submission documentsIncreaseOfShare', '', ''),
(223, 19, 121, 1, '2014-07-29 18:40:29', 'Delete after-submission documents IncreaseOfShare', '', ''),
(224, 19, 121, 1, '2014-07-29 18:41:14', 'Delete  set of documents fromIncreaseOfShare', '', ''),
(225, 20, 121, 1, '2014-07-31 14:53:47', 'Upload before-submission documents[Function: IncreasePaidUpCapital]', '', ''),
(226, 20, 121, 1, '2014-07-31 14:56:15', 'Download before submission documents IncreasePaidUpCapital', '', ''),
(227, 20, 121, 1, '2014-07-31 14:56:17', 'Download before submission documents IncreasePaidUpCapital', '', ''),
(228, 20, 121, 1, '2014-07-31 14:56:23', 'Delete before-submission documents IncreasePaidUpCapital', '', ''),
(229, 20, 121, 1, '2014-07-31 14:56:30', 'Upload before-submission documents[Function: IncreasePaidUpCapital]', '', ''),
(230, 20, 121, 1, '2014-07-31 14:56:32', 'Download before submission documents IncreasePaidUpCapital', '', ''),
(231, 20, 121, 1, '2014-07-31 14:56:34', 'Delete before-submission documents IncreasePaidUpCapital', '', ''),
(232, 20, 121, 1, '2014-07-31 14:56:52', 'Upload after-submission documents[Function: IncreasePaidUpCapital]', '', ''),
(233, 20, 121, 1, '2014-07-31 14:57:06', 'Upload after-submission documents[Function: IncreasePaidUpCapital]', '', ''),
(234, 20, 121, 1, '2014-07-31 14:58:16', 'Upload after-submission documents[Function: IncreasePaidUpCapital]', '', ''),
(235, 20, 121, 1, '2014-07-31 14:58:17', ' Download after submission documentsIncreasePaidUpCapital', '', ''),
(236, 20, 121, 1, '2014-07-31 14:58:19', 'Delete after-submission documents IncreasePaidUpCapital', '', ''),
(237, 20, 121, 1, '2014-07-31 14:58:21', 'Delete  set of documents fromIncreasePaidUpCapital', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `first_final_dividend`
--

CREATE TABLE IF NOT EXISTS `first_final_dividend` (
  `document_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `first_final_dividend`
--

INSERT INTO `first_final_dividend` (`document_id`, `type`) VALUES
(241, 'interim');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE IF NOT EXISTS `forms` (
  `form_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`form_id`, `name`, `description`) VALUES
(1, 'Form 45', 'Requires company and director details'),
(2, 'Form 49', 'Requires one or more directors details'),
(3, 'Resolution', 'Form description'),
(4, 'ResignationLetter', ''),
(5, 'indemnityLetter', ''),
(6, 'Form 45B', 'Consent to act as secretary'),
(7, 'form11', 'form 11 for Changing company name\r\n'),
(8, 'EOGM', ''),
(9, 'Option To Purchase', ''),
(10, 'form24', ''),
(11, 'form44', ''),
(12, 'FormParticular', ''),
(13, 'BusinessProfile', ''),
(14, 'FormMAA', ''),
(15, 'Form9', ''),
(16, 'FormFirstMeeting', ''),
(17, 'RegisterOfApplicationsAndAI', ''),
(18, 'RegisterDirectorsAlternateD', ''),
(19, 'MemberForm', ''),
(20, 'DirectorInterest', ''),
(21, 'CertificationSH', ''),
(22, 'RegisterMortgagesCharges', ''),
(23, 'RegisterTransfer', ''),
(24, 'RegisterSealing', ''),
(25, 'SecretaryAuditor', ''),
(26, 'Form44A', 'NOTICE OF SITUATION OF REGISTERED OFFICE AND OF OFFICE HOURS AT TIME OF RESGISTRATION'),
(27, 'EGM', 'EGM'),
(28, 'letter to Acra', ''),
(29, 'Statutory Declaration', ''),
(30, 'form 94', ''),
(31, 'letterAllotMent', ''),
(32, 'form25', 'STATEMENT CONTAINING PARTICULARS OF SHARES		\r\nALLOTTED OTHERWISE THAN FOR CASH		\r\n		\r\n		\r\n ');

-- --------------------------------------------------------

--
-- Table structure for table `function_corps`
--

CREATE TABLE IF NOT EXISTS `function_corps` (
  `function_id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `function_name` varchar(100) NOT NULL,
  PRIMARY KEY (`function_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `function_corps`
--

INSERT INTO `function_corps` (`function_id`, `description`, `function_name`) VALUES
(0, 'Appoint and Resign Secretary\r\n', 'AppointResignS'),
(1, 'Appoint&Resign Directors', 'AppointResignD'),
(2, 'Appoint both Auditors & Secretary', 'AppointAs'),
(3, 'Change Of banking signatories UOB - Singly ', 'ChangeOfBankingSignatoriesUOB'),
(4, 'Change of company name', 'changeOfCompanyName'),
(5, 'Change Of Financial Year', 'changeOfFY'),
(6, 'Change of M&AA- 1 Directorship', 'changeOfMAA'),
(7, 'Closure Of Bank Account Resolution', 'ClosureOfBankAccResolution'),
(8, 'Loan Resolution for Mr Loaner', 'LoanResolution'),
(9, 'Option To Purchase', 'OptionToPurchase'),
(10, 'Incorporation', 'Incorporation'),
(11, 'Change Of Passport', 'ChangeOfPassport'),
(12, 'Change Registered Address', 'ChangeRegisteredAddress'),
(13, 'Sales of Assets and Business', 'SalesAssetBusiness'),
(14, 'Property Disposal', 'PropertyDisposal'),
(15, 'Appoint|Resign Auditors', 'ResignAuditor'),
(16, 'NormalStruckOff', 'NormalStruckOff'),
(17, 'Alloted to 2 PERSON-Direcotrs'' Fee', 'AllotDirectorFee'),
(18, 'First Final | Interim Dividend', 'firstFinalDividend'),
(19, 'Increase Of Shares', 'increaseOfShare\r\n'),
(20, 'Increase Capital Paid Up Other than Cash', 'IncreasePaidUpCapital');

-- --------------------------------------------------------

--
-- Table structure for table `incorporationdocument`
--

CREATE TABLE IF NOT EXISTS `incorporationdocument` (
  `document_id` int(11) NOT NULL,
  `chairman` varchar(100) NOT NULL,
  `directors` varchar(100) NOT NULL,
  `shareholders` varchar(100) NOT NULL,
  `auditor` varchar(100) NOT NULL,
  `secretary` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `incorporationdocument`
--

INSERT INTO `incorporationdocument` (`document_id`, `chairman`, `directors`, `shareholders`, `auditor`, `secretary`) VALUES
(215, 'MR CHAIRMAN', 'MR DIRECTOR 3,MR DIRECTOR 4,MR DIRECTOR 5', 'MR SHAREHOLDER 3,MR SHAREHOLDER 4,MR SHAREHOLDER 5', 'MR AUDITOR 1', 'MR SECRETARY 1');

-- --------------------------------------------------------

--
-- Table structure for table `increase_of_share`
--

CREATE TABLE IF NOT EXISTS `increase_of_share` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `increase_paidup_capital`
--

CREATE TABLE IF NOT EXISTS `increase_paidup_capital` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `increase_paidup_capital`
--

INSERT INTO `increase_paidup_capital` (`document_id`) VALUES
(246);

-- --------------------------------------------------------

--
-- Table structure for table `loan_resolution`
--

CREATE TABLE IF NOT EXISTS `loan_resolution` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `normal_struck_off`
--

CREATE TABLE IF NOT EXISTS `normal_struck_off` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `normal_struck_off`
--

INSERT INTO `normal_struck_off` (`document_id`) VALUES
(229),
(230);

-- --------------------------------------------------------

--
-- Table structure for table `option_to_purchase`
--

CREATE TABLE IF NOT EXISTS `option_to_purchase` (
  `document_id` int(11) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pdf`
--

CREATE TABLE IF NOT EXISTS `pdf` (
  `pdf_id` int(10) NOT NULL AUTO_INCREMENT,
  `form_id` int(10) NOT NULL,
  `company_id` int(10) NOT NULL,
  `pdf_url` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`pdf_id`),
  KEY `form_id` (`form_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `propery_disposal`
--

CREATE TABLE IF NOT EXISTS `propery_disposal` (
  `document_id` int(11) NOT NULL,
  `propertyName` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `propery_disposal`
--

INSERT INTO `propery_disposal` (`document_id`, `propertyName`, `price`) VALUES
(216, '123 Orchard Road', '28.00');

-- --------------------------------------------------------

--
-- Table structure for table `resign_auditor`
--

CREATE TABLE IF NOT EXISTS `resign_auditor` (
  `document_id` int(11) NOT NULL,
  `auditorName` varchar(100) NOT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_asset_business`
--

CREATE TABLE IF NOT EXISTS `sales_asset_business` (
  `document_id` int(11) NOT NULL,
  `price` double DEFAULT NULL,
  `buyer` varchar(100) DEFAULT NULL,
  `seller` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_asset_business`
--

INSERT INTO `sales_asset_business` (`document_id`, `price`, `buyer`, `seller`) VALUES
(214, 21, 'ABC', 'Dreamsmart');

-- --------------------------------------------------------

--
-- Table structure for table `secretaries`
--

CREATE TABLE IF NOT EXISTS `secretaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Mode` varchar(50) DEFAULT NULL,
  `Occupation` varchar(100) DEFAULT NULL,
  `OtherOccupation` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=598 ;

--
-- Dumping data for table `secretaries`
--

INSERT INTO `secretaries` (`id`, `Mode`, `Occupation`, `OtherOccupation`) VALUES
(589, 'appointed', 'SECRETARY', ''),
(597, 'appointed', 'SECRETARY', '');

-- --------------------------------------------------------

--
-- Table structure for table `shareholders`
--

CREATE TABLE IF NOT EXISTS `shareholders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `primary_address` varchar(100) DEFAULT NULL,
  `addressline1_Singapore` varchar(100) DEFAULT NULL,
  `addressline2_Singapore` varchar(100) DEFAULT NULL,
  `addressline3_Singapore` varchar(100) DEFAULT NULL,
  `addressline1_OverSea` varchar(100) DEFAULT NULL,
  `addressline2_OverSea` varchar(100) DEFAULT NULL,
  `addressline3_OverSea` varchar(100) DEFAULT NULL,
  `addressline1_Other` varchar(100) DEFAULT NULL,
  `addressline2_Other` varchar(100) DEFAULT NULL,
  `addressline3_Other` varchar(100) DEFAULT NULL,
  `CertificateNo` varchar(100) DEFAULT NULL,
  `NationalityatBirth` varchar(100) DEFAULT NULL,
  `Occupation` varchar(100) DEFAULT NULL,
  `NumberofShares` double DEFAULT NULL,
  `NumberofSharesInwords` varchar(100) DEFAULT NULL,
  `DateofBirth` varchar(100) DEFAULT NULL,
  `ClassofShares` varchar(100) DEFAULT NULL,
  `Currency` varchar(100) DEFAULT NULL,
  `Placeofbirth` varchar(100) DEFAULT NULL,
  `Nricdateofissue` varchar(100) DEFAULT NULL,
  `nricplaceofissue` varchar(100) DEFAULT NULL,
  `passportno` varchar(100) DEFAULT NULL,
  `passportdateofissue` varchar(100) DEFAULT NULL,
  `passportplaceofissue` varchar(100) DEFAULT NULL,
  `Remarks` varchar(100) DEFAULT NULL,
  `MembersRegisterNo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=597 ;

--
-- Dumping data for table `shareholders`
--

INSERT INTO `shareholders` (`id`, `primary_address`, `addressline1_Singapore`, `addressline2_Singapore`, `addressline3_Singapore`, `addressline1_OverSea`, `addressline2_OverSea`, `addressline3_OverSea`, `addressline1_Other`, `addressline2_Other`, `addressline3_Other`, `CertificateNo`, `NationalityatBirth`, `Occupation`, `NumberofShares`, `NumberofSharesInwords`, `DateofBirth`, `ClassofShares`, `Currency`, `Placeofbirth`, `Nricdateofissue`, `nricplaceofissue`, `passportno`, `passportdateofissue`, `passportplaceofissue`, `Remarks`, `MembersRegisterNo`) VALUES
(586, '1', 'SHAREHOLDER ROAD 1', 'STREET S1', '#11-11 MALAYSIA 111111', '', '', '', '', '', '', '1', 'MALAYSIAN', 'DIRECTOR', 40, 'Forty', '11-11-1971', 'ORDINARY', 'UNITED STATES DOLLARS', 'MALAYSIA', '11-11-1991', 'MALAYSIA', '', '', '', '', '1'),
(587, '1', 'SHAREHOLDER ROAD 2', 'STREET S2', '#22-22 SINGAPORE 222222', '', '', '', '', '', '', '2', 'MALAYSIAN', 'DIRECTOR', 30, 'Thirty""', '22-12-1972', 'Ordinary', 'UNITED STATES DOLLARS', 'SNGAPORE', '22-12-1992', 'SINGAPORE', '', '', '', '', '2'),
(588, '1', 'SHAREHOLDER ROAD 3', 'STREET S3', '#33-33 MALAYSIA 333333', '', '', '', '', '', '', '3', 'MALAYSIAN', 'DIRECTOR', 30, 'Thirty', '13-03-1973', 'Ordinary', 'UNITED STATES DOLLARS', 'MALAYSIA', '13-03-1993', 'MALAYSIA', '', '', '', '', '3'),
(594, '1', 'SHAREHOLDER ROAD 1', 'STREET S1', '#11-11 MALAYSIA 111111', '', '', '', '', '', '', '1', 'MALAYSIAN', 'DIRECTOR', 40, 'Forty', '11-11-1971', 'ORDINARY', 'UNITED STATES DOLLARS', 'MALAYSIA', '11-11-1991', 'MALAYSIA', '', '', '', '', '1'),
(595, '1', 'SHAREHOLDER ROAD 2', 'STREET S2', '#22-22 SINGAPORE 222222', '', '', '', '', '', '', '2', 'MALAYSIAN', 'DIRECTOR', 30, 'Thirty""', '22-12-1972', 'Ordinary', 'UNITED STATES DOLLARS', 'SNGAPORE', '22-12-1992', 'SINGAPORE', '', '', '', '', '2'),
(596, '1', 'SHAREHOLDER ROAD 3', 'STREET S3', '#33-33 MALAYSIA 333333', '', '', '', '', '', '', '3', 'MALAYSIAN', 'DIRECTOR', 30, 'Thirty', '13-03-1973', 'Ordinary', 'UNITED STATES DOLLARS', 'MALAYSIA', '13-03-1993', 'MALAYSIA', '', '', '', '', '3');

-- --------------------------------------------------------

--
-- Table structure for table `stakeholders`
--

CREATE TABLE IF NOT EXISTS `stakeholders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nric` varchar(100) NOT NULL,
  `address_1` varchar(100) NOT NULL,
  `address_2` varchar(100) NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `Director` tinyint(1) NOT NULL,
  `Secretary` tinyint(1) NOT NULL,
  `Auditor` tinyint(1) NOT NULL,
  `Shareholder` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stakeholders_ibfk_1` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=604 ;

--
-- Dumping data for table `stakeholders`
--

INSERT INTO `stakeholders` (`id`, `company_id`, `name`, `nric`, `address_1`, `address_2`, `nationality`, `created_at`, `updated_at`, `Director`, `Secretary`, `Auditor`, `Shareholder`) VALUES
(583, 121, 'MR DIRECTOR 1', 'S1234567A', 'DIRECTORS ROAD 1 ', 'STREET D1 #01-01 SINGAPORE 010101', 'SINGAPOREAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 1, 0, 0, 0),
(584, 121, 'MR DIRECTOR 2', '987654-32-1098', 'DIRECTORS ROAD 2', 'STREET D2 #02-02 MALAYSIA 020202', 'MALAYSIAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 1, 0, 0, 0),
(585, 121, 'MR DIRECTOR 3', 'S7654321A', 'DIRECTORS ROAD 3', 'STREET D3 #03-03 SINGAPORE 030303', 'SINGAPOREAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 1, 0, 0, 0),
(586, 121, 'MR SHAREHOLDER 1', '676751-65-6763', 'SHAREHOLDER ROAD 1', 'STREET S1 #11-11 MALAYSIA 111111', 'MALAYSIAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 0, 0, 0, 1),
(587, 121, 'MR SHAREHOLDER 2', 'S9876543A', 'SHAREHOLDER ROAD 2', 'STREET S2 #22-22 SINGAPORE 222222', 'SINGAPOREAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 0, 0, 0, 1),
(588, 121, 'MR SHAREHOLDER 3', '910723-41-9450', 'SHAREHOLDER ROAD 3', 'STREET S3 #33-33 MALAYSIA 333333', 'MALAYSIAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 0, 0, 0, 1),
(589, 121, 'MR SECRETARY', 'S0192837B', 'SECRETARY ROAD 1', 'STREET SEC 1 #09-09 SINGAPORE 676723', 'SINGAPOREAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 0, 1, 0, 0),
(590, 121, 'MR AUDITOR', 'S5647382Z', 'AUDITOR ROAD 1', 'STREET A1 #03-33 SINGAPORE 689218', 'SINGAPOREAN', '2014-07-18 16:55:19', '0000-00-00 00:00:00', 0, 0, 1, 0),
(591, 122, 'MR DIRECTOR 3', 'S1234567A', 'DIRECTORS ROAD 1 ', 'STREET D1 #01-01 SINGAPORE 010101', 'SINGAPOREAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 1, 0, 0, 0),
(592, 122, 'MR DIRECTOR 4', '987654-32-1098', 'DIRECTORS ROAD 2', 'STREET D2 #02-02 MALAYSIA 020202', 'MALAYSIAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 1, 0, 0, 0),
(593, 122, 'MR DIRECTOR 5', 'S7654321A', 'DIRECTORS ROAD 3', 'STREET D3 #03-03 SINGAPORE 030303', 'SINGAPOREAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 1, 0, 0, 0),
(594, 122, 'MR SHAREHOLDER 3', '676751-65-6763', 'SHAREHOLDER ROAD 1', 'STREET S1 #11-11 MALAYSIA 111111', 'MALAYSIAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 0, 0, 0, 1),
(595, 122, 'MR SHAREHOLDER 4', 'S9876543A', 'SHAREHOLDER ROAD 2', 'STREET S2 #22-22 SINGAPORE 222222', 'SINGAPOREAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 0, 0, 0, 1),
(596, 122, 'MR SHAREHOLDER 5', '910723-41-9450', 'SHAREHOLDER ROAD 3', 'STREET S3 #33-33 MALAYSIA 333333', 'MALAYSIAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 0, 0, 0, 1),
(597, 122, 'MR SECRETARY 1', 'S0192837B', 'SECRETARY ROAD 1', 'STREET SEC 1 #09-09 SINGAPORE 676723', 'SINGAPOREAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 0, 1, 0, 0),
(598, 122, 'MR AUDITOR 1', 'S5647382Z', 'AUDITOR ROAD 1', 'STREET A1 #03-33 SINGAPORE 689218', 'SINGAPOREAN', '2014-07-23 16:32:25', '0000-00-00 00:00:00', 0, 0, 1, 0),
(600, 121, 'Mr Auditor 2', '0p0p0p', '3 LOP', '4 jskfd sdfsdf', 'sdfsdf', '2014-07-24 17:18:55', '0000-00-00 00:00:00', 0, 0, 1, 0),
(601, 121, 'Mr ShareHolder 4', 'S91304001', 'shareholder 3 road', 'singapore 12890', 'Singaporean', '2014-07-29 13:43:24', '2014-07-29 13:43:24', 0, 0, 0, 1),
(602, 121, 'Mr ShareHolder 5', 'S91304002', 'shareholder 4 road', 'singapore 12891', 'Singaporean', '2014-07-29 13:43:52', '2014-07-29 13:43:52', 0, 0, 0, 1),
(603, 121, 'Mr ShareHolder 6', 'S91304003', 'shareholder 5 road', 'singapore 12894', 'Singaporean', '2014-07-29 13:44:21', '2014-07-29 13:44:21', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `firstName`, `lastName`, `id`, `token`, `lastLogin`) VALUES
('dreamsmart', '2c5778d53d54d71f463640ff6a60147fa449cfdc', 'Jayden', 'Do', 1, '258a504a485d52d1b4012035ee4f21778a5b6a0a', '2014-07-31 11:42:39');

-- --------------------------------------------------------

--
-- Table structure for table `zip_files`
--

CREATE TABLE IF NOT EXISTS `zip_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `company_id` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `function_id` (`function_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=205 ;

--
-- Dumping data for table `zip_files`
--

INSERT INTO `zip_files` (`id`, `path`, `company_id`, `function_id`, `created_at`) VALUES
(199, 'FirstFinalDividend2014-07-28 23-52-56.zip', 121, 18, '2014-07-28 23:52:56'),
(200, 'IncreaseOfShare2014-07-29 18-27-48.zip', 121, 18, '2014-07-29 18:27:48'),
(201, 'IncreaseOfShare2014-07-29 18-29-26.zip', 121, 19, '2014-07-29 18:29:26'),
(202, 'IncreasePaidUpCapital2014-07-31 12-55-27.zip', 121, 19, '2014-07-31 12:55:27'),
(203, 'IncreasePaidUpCapital2014-07-31 12-56-23.zip', 121, 19, '2014-07-31 12:56:23'),
(204, 'IncreasePaidUpCapital2014-07-31 12-58-56.zip', 121, 20, '2014-07-31 12:58:56');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allot_director_fee`
--
ALTER TABLE `allot_director_fee`
  ADD CONSTRAINT `allot_director_fee_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appoint_auditors_secretaries`
--
ALTER TABLE `appoint_auditors_secretaries`
  ADD CONSTRAINT `aus_fk1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aus_fk2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aus_fk3` FOREIGN KEY (`secretary_id`) REFERENCES `secretaries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appoint_resign_directors`
--
ALTER TABLE `appoint_resign_directors`
  ADD CONSTRAINT `appoint_resign_directors_ibfk_1` FOREIGN KEY (`director_id`) REFERENCES `stakeholders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appoint_resign_directors_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appoint_resign_directors_ibfk_3` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appoint_resign_secretaries`
--
ALTER TABLE `appoint_resign_secretaries`
  ADD CONSTRAINT `appoint_resign_secretaries_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appoint_resign_secretaries_ibfk_2` FOREIGN KEY (`secretary_id`) REFERENCES `stakeholders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ars_fk2` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auditors`
--
ALTER TABLE `auditors`
  ADD CONSTRAINT `auditor_fk1` FOREIGN KEY (`id`) REFERENCES `stakeholders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_bank_signator_uob`
--
ALTER TABLE `change_bank_signator_uob`
  ADD CONSTRAINT `cbsu_fk1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsu_fk2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cbsu_fk3` FOREIGN KEY (`director_id`) REFERENCES `directors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_company_names`
--
ALTER TABLE `change_company_names`
  ADD CONSTRAINT `ccn_fk1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ccn_fk2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_financial_years`
--
ALTER TABLE `change_financial_years`
  ADD CONSTRAINT `change_financial_years_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_maa`
--
ALTER TABLE `change_maa`
  ADD CONSTRAINT `change_maa_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_passport`
--
ALTER TABLE `change_passport`
  ADD CONSTRAINT `change_passport_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `change_registered_address`
--
ALTER TABLE `change_registered_address`
  ADD CONSTRAINT `change_registered_address_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `closure_bank_acc`
--
ALTER TABLE `closure_bank_acc`
  ADD CONSTRAINT `closureBA_fk1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `directors`
--
ALTER TABLE `directors`
  ADD CONSTRAINT `directors_fk1` FOREIGN KEY (`id`) REFERENCES `stakeholders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_fk1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `documents_fk2` FOREIGN KEY (`function_id`) REFERENCES `function_corps` (`function_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_fk1` FOREIGN KEY (`function_id`) REFERENCES `function_corps` (`function_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `events_fk2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `events_fk3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `first_final_dividend`
--
ALTER TABLE `first_final_dividend`
  ADD CONSTRAINT `first_final_dividend_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incorporationdocument`
--
ALTER TABLE `incorporationdocument`
  ADD CONSTRAINT `inc_fk1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `increase_of_share`
--
ALTER TABLE `increase_of_share`
  ADD CONSTRAINT `increase_of_share_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `increase_paidup_capital`
--
ALTER TABLE `increase_paidup_capital`
  ADD CONSTRAINT `increase_paidup_capital_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan_resolution`
--
ALTER TABLE `loan_resolution`
  ADD CONSTRAINT `loanresolution_fk1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `normal_struck_off`
--
ALTER TABLE `normal_struck_off`
  ADD CONSTRAINT `normal_struck_off_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `option_to_purchase`
--
ALTER TABLE `option_to_purchase`
  ADD CONSTRAINT `otp_fk1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pdf`
--
ALTER TABLE `pdf`
  ADD CONSTRAINT `pdf_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`form_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pdf_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE;

--
-- Constraints for table `propery_disposal`
--
ALTER TABLE `propery_disposal`
  ADD CONSTRAINT `propery_disposal_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resign_auditor`
--
ALTER TABLE `resign_auditor`
  ADD CONSTRAINT `resign_auditor_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_asset_business`
--
ALTER TABLE `sales_asset_business`
  ADD CONSTRAINT `sales_asset_business_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `secretaries`
--
ALTER TABLE `secretaries`
  ADD CONSTRAINT `sec_fk1` FOREIGN KEY (`id`) REFERENCES `stakeholders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shareholders`
--
ALTER TABLE `shareholders`
  ADD CONSTRAINT `shareholders_fk1` FOREIGN KEY (`id`) REFERENCES `stakeholders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stakeholders`
--
ALTER TABLE `stakeholders`
  ADD CONSTRAINT `stakeholders_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `zip_files`
--
ALTER TABLE `zip_files`
  ADD CONSTRAINT `zip_files_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zip_files_ibfk_2` FOREIGN KEY (`function_id`) REFERENCES `function_corps` (`function_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
