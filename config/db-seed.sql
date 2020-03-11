# /docker-entrypoint-initdb.d/db-seed.sql

# create user
CREATE USER IF NOT EXISTS 'admin'@'%' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON * . * TO 'admin'@'%';
FLUSH PRIVILEGES;

# create db
CREATE DATABASE IF NOT EXISTS `data`;

# create table
CREATE TABLE IF NOT EXISTS `data` (
	`id` int(11) NOT NULL auto_increment,   
	`firstname` varchar(100)  NOT NULL,
	`lastname` varchar(100)  NOT NULL,
	`email` varchar(100)  NOT NULL,
	`reason` varchar(20)  NOT NULL,
	PRIMARY KEY  (`id`)
);
