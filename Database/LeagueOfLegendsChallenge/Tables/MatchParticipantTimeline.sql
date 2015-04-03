/*
MatchParticipantTimeline
This table will hold more specific participant timeline data
for each match recorded. 

Each record in this table is based off of the data 
retrieved from the "participantFrames" object of
match. 

FK:
	fk_MPT_matchId references MatchHeader
*/
CREATE TABLE `MatchParticipantTimeline` (
  `matchId` int(11) NOT NULL,
  `teamId` int(11) DEFAULT NULL,
  `participantId` int(11) DEFAULT NULL,
  `pos_x` int(11) DEFAULT NULL,
  `pos_y` int(11) DEFAULT NULL,
  `currentGold` int(11) DEFAULT NULL,
  `totalGold` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `xp` int(11) DEFAULT NULL,
  `minionsKilled` int(11) DEFAULT NULL,
  `jungleMinionsKilled` int(11) DEFAULT NULL,
  `dominionScore` int(11) DEFAULT NULL,
  `teamScore` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  KEY `fk_MPT_matchId_idx` (`matchId`),
  CONSTRAINT `fk_MPT_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
