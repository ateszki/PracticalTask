CREATE SCHEMA `PracticalTask` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;

CREATE  TABLE `PracticalTask`.`contacts` (
  `Id` INT NOT NULL AUTO_INCREMENT ,
  `FirstName` VARCHAR(500) NULL ,
  `LastName` VARCHAR(500) NULL ,
  `Country` VARCHAR(500) NULL ,
  `City` VARCHAR(500) NULL ,
  `Address` VARCHAR(500) NULL ,
  `Email` VARCHAR(500) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = MyISAM;

GRANT ALL PRIVILEGES ON PracticalTask.* To 'PTaskUser'@'localhost' IDENTIFIED BY 'Ghte7u6gI33';

INSERT INTO `PracticalTask`.`contacts`
(`FirstName`,`LastName`,`Country`,`City`,`Address`,`Email`)
VALUES
('John','Doe','United States','New York', '4 New York Plaza, New York, NY 10004','johndoe@gmail.com'),
('Michael','Smith','United States','New York', '4 New York Plaza, New York, NY 10004','michaelsmith@gmail.com');
