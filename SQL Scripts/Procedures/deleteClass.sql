DELIMITER //

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteClass`(crn int)
BEGIN
DELETE FROM COURSE
WHERE COURSE_CRN=crn;
END


// DELIMITER ;