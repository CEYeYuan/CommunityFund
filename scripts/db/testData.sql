-- -----------------------------------------------
-- MySQL Testing Temp Data Script
-- -----------------------------------------------

-- -----------------------------------------------
--                GENERAL NOTES
--
-- - This script is simply to put in dummy data to play with for a testing environment
-- - This script assumes that the cfProductionSetup.sql script has been run
-- - This script must be run immediately after creating the database
-- -----------------------------------------------

-- Select database to use
USE cfProduction;

-- Category table
Insert INTO Category (name, description, pic, active) VALUES ('Arts', 'Art-related stuff', '', 1),
	('Crafts', 'Craft-related stuff', '', 1),
	('Books', 'Books and novels', '', 1);
	
-- Project table
-- 'now()' is a MySQL built-in function that gets the current date and time
INSERT INTO Project (pname, description, fundsNeeded, startDate, endDate, postDate, pic, active) VALUES ('Project1', 'The description for Project1, which is a test project', 100.00, now(), '2015-06-23 10:00:00', now(), '', 1),
	('Project2', 'The description for Project2, which is a test project', 200.00, now(), '2016-06-23 10:00:00', now(), '', 1),
	('Project3', 'The description for Project3, which is.... another test project', 1000.00, now(), '2017-06-23 10:00:00', now(), '', 1);

-- User table
-- all users in temp data have password '123'
-- hash value for password of '123': 202cb962ac59075b964b07152d234b70
INSERT INTO User (username, password, firstName, lastName, email, pic, admin, active) VALUES ('admin@admin.com', '202cb962ac59075b964b07152d234b70', 'admin', 'admin', 'admin@admin.com', '', 1, 1),
	('user@user.com', '202cb962ac59075b964b07152d234b70', 'user', 'user', 'user@user.com', '', 0, 1),
	('user2@user2.com', '202cb962ac59075b964b07152d234b70', 'user2', 'user2', 'user2@user2.com', '', 0, 1),
	('user3@user3.com', '202cb962ac59075b964b07152d234b70', 'user3', 'user3', 'user3@user3.com', '', 0, 1);

-- Initiator table
-- admin and user are initiators
INSERT INTO Initiator (uid, active) VALUES ((SELECT uid FROM User WHERE username='admin@admin.com' AND active=1), 1),
	((SELECT uid FROM User WHERE username='user@user.com' AND active=1), 1);

-- Funder table
-- user2 and user3 are funders
INSERT INTO Funder (uid, active) VALUES ((SELECT uid FROM User WHERE username='user2@user2.com' AND active=1), 1),
	((SELECT uid FROM User WHERE username='user3@user3.com' AND active=1), 1);

-- RateProj table
-- user2 and user3 have rated Project2 and they have given it a thumbs up/like (1)
-- anyone can rate a project, which is why we use the 'uid'
INSERT INTO RateProj (uid, pid, date, rating) VALUES ((SELECT uid FROM User WHERE username='user2@user2.com' AND active=1), (SELECT pid FROM Project WHERE pname='Project2'), now(), 1),
	((SELECT uid FROM User WHERE username='user3@user3.com' AND active=1), (SELECT pid FROM Project WHERE pname='Project2'), now(), 1);
	
-- InitiateProj table
-- admin will initiate Project1 and Project2
-- user will initiate Project3
INSERT INTO InitiateProj (inid, pid) VALUES (1, 1), (1, 2), (2, 3);

-- ProjCat table
-- Project1 will be in Arts, Project2 and Project3 will be in Crafts
INSERT INTO ProjCat (pid, cid) VALUES (1, 1), (2, 2), (3, 2);

-- Comment table
INSERT INTO Comment (uid, pid, date, description, active) VALUES (1, 1, now(), "User admin commenting on Project1", 1),
	(1, 2, now(), "User admin commenting on Project2", 1),
	(3, 1, now(), "User user2 commenting on Project1", 1),
	(4, 1, now(), "User user3 commenting on Project1", 1),
	(4, 3, now(), "User user3 commenting on Project3", 1),
	(4, 4, now(), "User user3 commenting on Project4... he is a social butterfly", 1);

-- Fund table
-- user2 funds Project1
-- user2 funds Project2
-- user3 funds Project1
-- user2 funds Project3
INSERT INTO Fund (fid, pid, date, amount) VALUES (1, 1, now(), 2.00),
	(1, 2, now(), 60.00),
	(2, 1, now(), 10.00),
	(1, 3, now(), 15.00);

-- UpdateProj table
--
-- updates about how well the project is going
-- updates are provided by the initiator
-- 
-- admin updates Project1 and Project2
-- user updates Project3
INSERT INTO UpdateProj (inid, pid, date, description, active) VALUES (1, 1, now(), "Admin updating Project1: it is going great!", 1),
	(1, 1, now(), "Admin updating Project1: we have now run into a few difficulties", 1),
	(1, 1, now(), "Admin updating Project1: After our difficulties, things are moving well once again", 1),
	(1, 2, now(), "Admin updating Project2: Not much is going on here so far", 1),
	(1, 2, now(), "The project is still taking a while to get off the ground", 1),
	(1, 2, now(), "We need more money!", 1),
	(2, 3, now(), "User updating Project3: This is the only project I am initiator for", 1),
	(2, 3, now(), "User updating Project3: I am happy to be working on a project", 1);

-- RateF table
-- Initiators rate funders
INSERT INTO RateF (pid, rater, rated, rating, date) VALUES (1, 1, 1, 3, now()), -- inid(1) rates fid(1) for pid(1)
	(2, 1, 1, 4, now()),
	(3, 2, 1, 3, now());

-- RateI table
-- Funders rate initiators
INSERT INTO RateI (pid, rater, rated, rating, date) VALUES (1, 2, 1, 2, now()),
	(2, 1, 1, 5, now()); 
