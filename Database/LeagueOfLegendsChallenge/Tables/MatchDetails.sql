/*
MatchDetails
This table will hold any leftover match detail data
that isn't necessarily associated with team or participants

FK:
	fk_MD_matchId references MatchHeader matchId
*/
CREATE TABLE `MatchDetails` (
  `matchId` int(11) NOT NULL,
  `frameInterval` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`matchId`),
  CONSTRAINT `fk_MD_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
