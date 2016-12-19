
DROP DATABASE IF EXISTS bank;
CREATE DATABASE bank;
USE bank;

CREATE TABLE IF NOT EXISTS `account_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type` varchar(20) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO `account_types` (`id`, `account_type`) VALUES
(1, 'personal-checking'),
(2, 'personal-savings'),
(3, 'business-checking'),
(4, 'business-savings');

CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_first` varchar(255) NOT NULL,
  `name_last` varchar(255) NOT NULL,
  `name_middle` varchar(255) DEFAULT NULL,
  `name_user` varchar(255) DEFAULT NOT NULL,
  `password` varchar(255) DEFAULT NOT NULL,
  `date_birth` date NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `account_number` varchar(15) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE IF NOT EXISTS `contact_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_account` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `cur_bal` decimal(10,2) NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (id)
);



