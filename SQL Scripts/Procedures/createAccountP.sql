DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `createAccountP`(fname VARCHAR(20), lname VARCHAR(20), email VARCHAR(40), pass VARCHAR (20))
BEGIN
INSERT INTO PROFESSOR (PROF_FNAME, PROF_LNAME, PROF_EMAIL, PROF_PASS)
VALUES (fname, lname, email, pass);
END
// DELIMITER ;