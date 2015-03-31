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
DROP TABLE IF EXISTS Project CASCADE; -- changed
DROP TABLE IF EXISTS User CASCADE;
DROP TABLE IF EXISTS Category CASCADE;
DROP TABLE IF EXISTS RateProj CASCADE;
DROP TABLE IF EXISTS UpdateProj CASCADE; -- changed
DROP TABLE IF EXISTS Comment CASCADE;
DROP TABLE IF EXISTS Fund CASCADE; -- changed
DROP TABLE IF EXISTS RateF CASCADE;
DROP TABLE IF EXISTS RateI CASCADE;
DROP TABLE IF EXISTS Feedback CASCADE;

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
    category 	INTEGER 		REFERENCES Category(cid) ON DELETE RESTRICT,
    initiator 	INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
    pic 		VARCHAR(200),
    active 		INTEGER 		NOT NULL);

-- username will be hashed
-- password will be hashed
-- email will be hashed
CREATE TABLE User (
    uid 		INTEGER AUTO_INCREMENT 		PRIMARY KEY,
    username 	VARCHAR(200) 	NOT NULL UNIQUE,
    password 	VARCHAR(200) 	NOT NULL,
    firstName 	VARCHAR(200) 	NOT NULL,
    lastName 	VARCHAR(200) 	NOT NULL,
    DateofBirth VARCHAR(20),
    gender 		VARCHAR(1),
    email 		VARCHAR(200) 	NOT NULL,
    pic			VARCHAR(200),
    admin 		INTEGER,
    active 		INTEGER);

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
	uid 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	date 		DATETIME 		NOT NULL,
	description TEXT 			NOT NULL,
	active 		INTEGER 		NOT NULL);

CREATE TABLE Comment (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	uid 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	date 		DATETIME 		NOT NULL,
	description TEXT 			NOT NULL,
	active 		INTEGER 		NOT NULL);

-- CORRECT/NEW
CREATE TABLE Fund (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	uid 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	date 		DATETIME 		NOT NULL,
	amount 		NUMERIC(15,2) 	NOT NULL,
	active 		INTEGER 		NOT NULL);

-- an initiator rates a funder
CREATE TABLE RateF (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	rater 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	rated 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	rating 		INTEGER 		NOT NULL,
	date 		DATETIME 		NOT NULL);

-- a funder rates an initiator
CREATE TABLE RateI (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	pid 		INTEGER 		REFERENCES Project(pid) ON DELETE RESTRICT,
	rater 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	rated 		INTEGER 		REFERENCES User(uid) ON DELETE RESTRICT,
	rating 		INTEGER 		NOT NULL,
	date 		DATETIME 		NOT NULL);

CREATE TABLE Feedback (
	id 			INTEGER AUTO_INCREMENT 		PRIMARY KEY,
	title 		VARCHAR(200) 	NOT NULL,
	type 		VARCHAR(200) 	NOT NULL,
	description TEXT 			NOT NULL);

CREATE TABLE Chathistory(
	sender INTEGER REFERENCES User(uid) ON DELETE RESTRICT,
	receiver INTEGER REFERENCES User(uid) ON DELETE RESTRICT,
	time DATETIME NOT NULL,
	msg VARCHAR(2000) NOT NULL,
	ifread INTEGER DEFAULT '-1' NOT NULL
);

-- insert test data
-- 202cb962ac59075b964b07152d234b70
INSERT INTO User (username, password, firstName, lastName, email, admin, active) VALUES 
	('davidlacoste3@gmail.com', '202cb962ac59075b964b07152d234b70', 'David', 'Lacoste', 'davidlacoste3@gmail.com', 1, 1);
INSERT INTO User (username, password, firstName, lastName, email, admin, active) VALUES 
	('user1@user1.com', '202cb962ac59075b964b07152d234b70', 'u1', 'u1', 'user1@user1.com', -1, 1);
INSERT INTO User (username, password, firstName, lastName, email, admin, active) VALUES 
	('user2@user2.com', '202cb962ac59075b964b07152d234b70', 'u2', 'u2', 'user2@user2.com', -1, 1);
INSERT INTO User (username, password, firstName, lastName, email, admin, active) VALUES 
	('user3@user3.com', '202cb962ac59075b964b07152d234b70', 'u3', 'u3', 'user3@user3.com', -1, 1);


INSERT INTO Category (name, description, pic, active) VALUES ('Arts', 'Art-related stuff', '', 1),
	('Crafts', 'Craft-related stuff', '', 1), ('Books', 'Books and novels', '', 1);

INSERT INTO Fund (uid, pid, date, amount, active) VALUES (1, 1, now(), '10.00', 1);

INSERT INTO Project (pname, description, fundsNeeded, startDate, endDate, postDate, category, initiator, pic, active) VALUES 
	('Project1', 'Description for project1', '100.00', now(), now(), now(), 1, 1, '', 1),
	('Project2', 'Description for project2', '200.00', now(), now(), now(), 1, 1, '', 1),
	('Project3', 'Description for project3', '300.00', now(), now(), now(), 2, 2, '', 1);

INSERT INTO RateProj (uid, pid, date, rating) VALUES (1, 1, now(), 1),
	(1, 2, now(), 0),
	(1, 1, now(), 1);
