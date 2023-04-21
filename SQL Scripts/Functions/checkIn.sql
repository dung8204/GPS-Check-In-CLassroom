DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `checkIn`(lat double, lon double, crn int, stuid int) RETURNS int(11)
BEGIN
DECLARE loc int; 
DECLARE x int;
DECLARE curdate DATETIME;
SET curdate = now();
SELECT CONVERT_TZ(curdate, '+00:00', '-04:00') INTO curdate;
SELECT DATE_FORMAT(curdate, '%Y-%m-%dT%H:%i') INTO curdate;

SELECT COUNT(*) INTO loc FROM COURSE, LOCATION WHERE COURSE_CRN = crn AND lat > LOCATION_LAT1 AND lat < LOCATION_LAT2 AND lon > LOCATION_LON1 AND lon < LOCATION_LON2;
SELECT ATTENDANCE_ID INTO x FROM ATTENDANCE WHERE TIMESTAMPDIFF(MINUTE, ATTENDANCE_DATETIME, curdate) > -15  AND TIMESTAMPDIFF(MINUTE, ATTENDANCE_DATETIME, curdate) < 15 AND COURSE_CRN = crn;


IF loc = 1 THEN
	IF x IS NOT NULL THEN
		UPDATE ATTENDANCE_RECORD SET ATT_REC_PRESENT = 1 WHERE ATTENDANCE_ID = x AND STU_ID = stuid AND COURSE_CRN = crn;
		RETURN 1;
	else
		RETURN 0;
	END IF;
else
	RETURN 0;
END IF; 

END
// DELIMITER ;