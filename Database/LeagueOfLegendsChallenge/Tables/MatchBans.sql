/*
MatchBans
This table will hold banned championIDs associated
with each match

FK:
	fk_MB_matchId references MatchHeader
*/
CREATE TABLE `MatchBans` (
  `matchId` int(11) NOT NULL,
  `firstBan` int(11) DEFAULT NULL,
  `secondBan` int(11) DEFAULT NULL,
  `thirdBan` int(11) DEFAULT NULL,
  `fourthBan` int(11) DEFAULT NULL,
  `fifthBan` int(11) DEFAULT NULL,
  `sixthBan` int(11) DEFAULT NULL,
  PRIMARY KEY (`matchId`),
  CONSTRAINT `fk_MB_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
