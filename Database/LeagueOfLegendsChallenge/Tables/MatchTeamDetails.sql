/*
MatchTeamDetails
Team details for each team per match

PK:
	matchId
    teamId

FK:
	fk_MPT_matchId references MatchHeader
*/
CREATE TABLE `MatchTeamDetails` (
  `matchId` int(11) NOT NULL,
  `teamId` int(11) NOT NULL,
  `baronKills` int(11) DEFAULT NULL,
  `dragonKills` int(11) DEFAULT NULL,
  `totalKills` int(11) DEFAULT NULL,
  `totalAssists` int(11) DEFAULT NULL,
  `totalDeaths` int(11) DEFAULT NULL,
  `totalGoldEarned` int(11) DEFAULT NULL,
  `totalGoldSpent` int(11) DEFAULT NULL,
  `winner` int(11) DEFAULT NULL,
  `firstBlood` int(11) DEFAULT NULL,
  `firstTower` int(11) DEFAULT NULL,
  `firstInhibitor` int(11) DEFAULT NULL,
  `firstBaron` int(11) DEFAULT NULL,
  `firstDragon` int(11) DEFAULT NULL,
  `vilemawKills` int(11) DEFAULT NULL,
  PRIMARY KEY (`matchId`,`teamId`),
  CONSTRAINT `fk_MTD_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
