-- -----------------------------------------------
-- MySQL Delete All Data Script
-- -----------------------------------------------

-- -----------------------------------------------
--                GENERAL NOTES
--
-- - Deletes all data from all tables and used for testing purposes
-- - This script assumes that the cfProductionSetup.sql script has been run
-- -----------------------------------------------

-- Select database to use
USE cfProduction;

-- Delete data
DELETE FROM Project;
DELETE FROM User;
DELETE FROM Initiator;
DELETE FROM Funder;
DELETE FROM Category;
DELETE FROM RateProj;
DELETE FROM UpdateProj;
DELETE FROM InitiateProj;
DELETE FROM Comment;
DELETE FROM Fund;
DELETE FROM ProjCat;
DELETE FROM RateF;
DELETE FROM RateI;
