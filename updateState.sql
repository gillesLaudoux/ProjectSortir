
DROP PROCEDURE IF EXISTS update_cloture_night_out;
DROP PROCEDURE IF EXISTS update_en_cours_night_out;
DROP PROCEDURE IF EXISTS update_passee_night_out;
DROP PROCEDURE IF EXISTS update_state_night_out;
DROP EVENT IF EXISTS update_night_out;

DELIMITER //
CREATE PROCEDURE update_cloture_night_out()
BEGIN
	UPDATE night_out SET state_id = 3  
	WHERE due_date_inscription < NOW() AND starting_time < NOW() ;
END //


DELIMITER //	
CREATE PROCEDURE update_en_cours_night_out()
BEGIN
	UPDATE night_out SET state_id = 4  
	WHERE starting_time < NOW() AND ending_time < NOW() ;
END //

DELIMITER //
CREATE PROCEDURE update_passee_night_out()
BEGIN
	UPDATE night_out SET state_id = 5  
	WHERE ending_time < NOW() ;
END //


DELIMITER //
CREATE PROCEDURE update_state_night_out()
BEGIN
	CALL update_cloture_night_out();
	CALL update_en_cours_night_out();
	CALL update_passee_night_out();
END //


DELIMITER //
CREATE EVENT update_night_out
ON SCHEDULE EVERY 5 MINUTE
ON COMPLETION PRESERVE
DO
	BEGIN	
		CALL update_state_night_out();
	END //

SET GLOBAL event_scheduler='ON';