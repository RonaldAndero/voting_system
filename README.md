
# Simple voting system

This is a voting system project designed for organizing and conducting votes. It includes features for creating voting sessions, registering voters, recording their votes, and tracking the vote results.

Note: Please be aware that the script for creating the database fully automatically is still unfortunately missing. You will need to follow the installation steps provided and manually run the SQL queries in phpMyAdmin to set up the required database tables.


## Installation

1. Download [XAMPP](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.4/) and install it on your system.

2. During the installation process, make sure that both phpmyadmin and mysql are selected.

3. Clone or download the repository and move it to the htdocs folder inside the XAMPP directory.

4. Start the Apache and MySQL modules in XAMPP control panel.

5. Open your web browser and navigate to localhost/phpmyadmin.

6. Create a new database called "haaletussusteem".

7. Run the following SQL queries in the SQL tab of phpMyAdmin:

```sql
  CREATE TABLE HAALETUS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eesnimi VARCHAR(50),
    perenimi VARCHAR(50),
    hääletuse_aeg DATETIME,
    otsus TINYINT(1)
);

CREATE TABLE TULEMUSED (
    id INT AUTO_INCREMENT PRIMARY KEY,
    h_alguse_aeg DATETIME,
    poolt INT,
    vastu INT,
    hääletanute_arv INT
);

CREATE TABLE LOGI (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hääletaja_id INT,
    aeg DATETIME,
    enne_otsus TINYINT(1),
    pärast_otsus TINYINT(1)
);

CREATE TABLE voting_session (
    status VARCHAR(6),
    start_time DATETIME
);

INSERT INTO voting_session (status, start_time) VALUES ('open', NOW());

INSERT INTO HAALETUS (eesnimi, perenimi, hääletuse_aeg, otsus) VALUES
('Jaan', 'Tamm', NOW(), NULL),
('Kati', 'Mets', NOW(), NULL),
('Priit', 'Kivi', NOW(), NULL),
('Mai', 'Lille', NOW(), NULL),
('Toomas', 'Puul', NOW(), NULL),
('Liina', 'Sepp', NOW(), NULL),
('Kalle', 'Allik', NOW(), NULL),
('Mari', 'Vaher', NOW(), NULL),
('Andres', 'Puu', NOW(), NULL),
('Tiina', 'Teder', NOW(), NULL),
('Marko', 'Lepp', NOW(), NULL);

DELIMITER $$
CREATE TRIGGER haal_update_trigger AFTER UPDATE ON HAALETUS
FOR EACH ROW
BEGIN
    INSERT INTO LOGI (hääletaja_id, aeg, enne_otsus, pärast_otsus)
    VALUES (OLD.id, NOW(), OLD.otsus, NEW.otsus);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tulemused_insert_trigger AFTER UPDATE ON voting_session
FOR EACH ROW
BEGIN
    DECLARE haaletajate_arv INT;
    DECLARE poolt INT;
    DECLARE vastu INT;
    
    IF NEW.status = 'closed' AND OLD.status <> 'closed' THEN
        SELECT COUNT(*) INTO haaletajate_arv FROM haaletus;
        SELECT SUM(otsus) INTO poolt FROM haaletus;
        SELECT SUM(1-otsus) INTO vastu FROM haaletus;
        INSERT INTO tulemused (h_alguse_aeg, poolt, vastu, hääletanute_arv)
        VALUES (OLD.start_time, poolt, vastu, haaletajate_arv);
    END IF;
END$$
DELIMITER ;

SET GLOBAL event_scheduler = ON;

CREATE EVENT close_voting_session
ON SCHEDULE EVERY 1 MINUTE
DO
  UPDATE voting_session 
  SET status = 'closed'
  WHERE status = 'open'
  AND start_time <= NOW() - INTERVAL 5 MINUTE;

```
8. You should now be able to use the application by navigating to localhost in your web browser

## Authors

- [@RonaldAndero](https://github.com/RonaldAndero)
- [@MarkosPaltser](https://github.com/Paltser)

