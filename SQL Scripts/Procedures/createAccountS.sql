DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `createAccountS`(fname VARCHAR(20), lname VARCHAR(20), email VARCHAR(40), pass VARCHAR (20))
BEGIN
INSERT INTO STUDENT (STU_FNAME, STU_LNAME, STU_EMAIL, STU_PASS)
VALUES (fname, lname, email, pass);
END
// DELIMITER ;