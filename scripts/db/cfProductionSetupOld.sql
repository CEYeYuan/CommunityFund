-- -----------------------------------------------
-- cfProduction MySQL Database Setup Script
-- -----------------------------------------------

-- -----------------------------------------------
--                GENERAL NOTES
--
-- - DATETIME is a MySQL data type only
-- - All pictures will be stored as relative path to pic on filesystem
-- -----------------------------------------------

-- Create the database
-- Comment out these first 2 lines if the database is already created
-- and you only need to set up the schema
DROP DATABASE cfProduction;
CREATE DATABASE cfProduction;
USE cfProduction;
SET auto_increment_increment=1;

-- Drop all tables if they already exist to rebuild schema
DROP TABLE IF EXISTS Project CASCADE;
DROP TABLE IF EXISTS User CASCADE;
DROP TABLE IF EXISTS Initiator CASCADE;
DROP TABLE IF EXISTS Funder CASCADE;
DROP TABLE IF EXISTS Category CASCADE;
DROP TABLE IF EXISTS RateProj CASCADE;
DROP TABLE IF EXISTS UpdateProj CASCADE;
DROP TABLE IF EXISTS InitiateProj CASCADE;
DROP TABLE IF EXISTS Comment CASCADE;
DROP TABLE IF EXISTS Fund CASCADE;
DROP TABLE IF EXISTS ProjCat CASCADE;
DROP TABLE IF EXISTS RateF CASCADE;
DROP TABLE IF EXISTS RateI CASCADE;

-- Create structure for all tables

-- postDate is the date where the project was created
-- startDate is when the project is funded and starts
-- endDate is when the project is closed/completed
CREATE TABLE Project (
    pid 		INTEGER AUTO_INCREMENT 		PRIMARY KEY,
    pname 		VARCHAR(200) 	NOT NULL,
    description TEXT 			NOT NULL,
    fundsNeeded NUMERIC(15,2) 	NOT NULL,
    startDate 	DATETIME 		NOT NULL,
    endDate 	DATETIME,
    postDate 	DATETIME 		NOT NULL,
    pic 		VARCHAR(200),
    active 		INTEGER 		NOT NULL);

-- username will be hashed
-- password will be hashed
-- email will be hashed
CREATE TABLE User (
    uid 		INTEGER AUTO_INCREMENT 		PRIMARY KEY,
    username 	VARCHAR(200) 	NOT NULL,
    password 	VARCHAR(200) 	NOT NULL,
    firstName 	VARCHAR(200) 	NOT NULL,
    lastName 	VARCHAR(200) 	NOT NULL,
    DateofBirth VARCHAR(20),
    gender 		VARCHAR(1),
    email 		VARCHAR(200) 	NOT NULL,
    pic			VARCHAR(200),
    admin 		INTEGER,
    active 		INTEGER);

CREATE TABLE Initiator (
    inid 		INTEGER AUTO_INCREMENT 		PRIMARY KEY,
    uid 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
    active 		INTEGER 		NOT NULL);

CREATE TABLE Funder (
    fid 		INTEGER AUTO_INCREMENT 		PRIMARY KEY,
    uid 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
    active 		INTEGER 		NOT NULL);

-- when creating a category, it is mandatory to include a pic for the category
-- only admins can create categories
CREATE TABLE Category (
    cid 		INTEGER AUTO_INCREMENT 		PRIMARY KEY,
    name 		VARCHAR(20) 	NOT NULL,
    description TEXT 			NOT NULL,
    pic 		VARCHAR(200) 	NOT NULL,
    active 		INTEGER 		NOT NULL);

CREATE TABLE RateProj (
    id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
    uid 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
    pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
    date 		DATETIME 		NOT NULL,
    rating 		INTEGER 		NOT NULL);

CREATE TABLE UpdateProj (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	inid 		INTEGER 		REFERENCES Initiator(inid) ON DELETE RESTRICT,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	date 		DATETIME 		NOT NULL,
	description TEXT 			NOT NULL,
	active 		INTEGER 		NOT NULL);

CREATE TABLE InitiateProj (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	inid 		INTEGER 		REFERENCES Initiator(inid) ON DELETE RESTRICT,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT);

CREATE TABLE Comment (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	uid 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	date 		DATETIME 		NOT NULL,
	description TEXT 			NOT NULL,
	active 		INTEGER 		NOT NULL);

CREATE TABLE Fund (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	fid 		INTEGER 		REFERENCES Funder(fid) ON DELETE RESTRICT,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	date 		DATETIME 		NOT NULL,
	amount 		NUMERIC(15,2) 	NOT NULL);

CREATE TABLE ProjCat (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	cid 		INTEGER 		REFERENCES Category(cid) ON DELETE RESTRICT);

-- an initiator rates a funder
CREATE TABLE RateF (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	rater 		INTEGER 		REFERENCES Initiator(inid) ON DELETE RESTRICT,
	rated 		INTEGER 		REFERENCES Funder(fid) ON DELETE RESTRICT,
	rating 		INTEGER 		NOT NULL,
	date 		DATETIME 		NOT NULL);

-- a funder rates an initiator
CREATE TABLE RateI (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	rater 		INTEGER 		REFERENCES Funder(fid) ON DELETE RESTRICT,
	rated 		INTEGER 		REFERENCES Initiator(inid) ON DELETE RESTRICT,
	rating 		INTEGER 		NOT NULL,
	date 		DATETIME 		NOT NULL);

CREATE TABLE Feedback (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	title 		VARCHAR(200) 	NOT NULL,
	type 		VARCHAR(200) 	NOT NULL,
	description TEXT 			NOT NULL);

