/*
MatchEvents
This table will hold any match events that occured
during a specific matchId

FK:
	fk_ME_matchId references MatchHeader matchId
*/
CREATE TABLE `MatchEvents` (
  `matchId` int(11) NOT NULL,
  `eventType` varchar(100) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `skillSlot` int(11) DEFAULT NULL,
  `participantId` int(11) DEFAULT NULL,
  `levelUpType` varchar(20) DEFAULT NULL,
  `itemId` int(11) DEFAULT NULL,
  `creatorId` int(11) DEFAULT NULL,
  `wardType` varchar(50) DEFAULT NULL,
  `killerId` int(11) DEFAULT NULL,
  `victimId` int(11) DEFAULT NULL,
  `assistingParticipantIds` varchar(10) DEFAULT NULL,
  `pos_x` int(11) DEFAULT NULL,
  `pos_y` int(11) DEFAULT NULL,
  `teamId` int(11) DEFAULT NULL,
  `laneType` varchar(50) DEFAULT NULL,
  `buildingType` varchar(50) DEFAULT NULL,
  `towerType` varchar(50) DEFAULT NULL,
  KEY `fk_ME_matchId_idx` (`matchId`),
  CONSTRAINT `fk_ME_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
