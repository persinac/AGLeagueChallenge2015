/*
MatchParticipantDetails
This table will hold specific participant data
for each match recorded

FK:
	fk_MPD_matchId references MatchHeader
*/
CREATE TABLE `MatchParticipantDetails` (
  `matchId` int(11) NOT NULL,
  `teamId` int(11) DEFAULT NULL,
  `participantId` int(11) DEFAULT NULL,
  `spell1Id` int(11) DEFAULT NULL,
  `spell2Id` int(11) DEFAULT NULL,
  `championId` int(11) DEFAULT NULL,
  `highestAchievedSeasonTier` varchar(45) DEFAULT NULL,
  `champLevel` int(11) DEFAULT NULL,
  `item0` int(11) DEFAULT NULL,
  `item1` int(11) DEFAULT NULL,
  `item2` int(11) DEFAULT NULL,
  `item3` int(11) DEFAULT NULL,
  `item4` int(11) DEFAULT NULL,
  `item5` int(11) DEFAULT NULL,
  `item6` int(11) DEFAULT NULL,
  `kills` int(11) DEFAULT NULL,
  `doubleKills` int(11) DEFAULT NULL,
  `tripleKills` int(11) DEFAULT NULL,
  `quadraKills` int(11) DEFAULT NULL,
  `pentaKills` int(11) DEFAULT NULL,
  `unrealKills` int(11) DEFAULT NULL,
  `largestKillingSpree` int(11) DEFAULT NULL,
  `deaths` int(11) DEFAULT NULL,
  `assists` int(11) DEFAULT NULL,
  `totalDamageDealt` int(10) unsigned zerofill DEFAULT NULL,
  `totalDamageDealtToChampions` int(11) DEFAULT NULL,
  `totalDamageTaken` int(11) DEFAULT NULL,
  `largestCriticalStrike` int(11) DEFAULT NULL,
  `totalHeal` int(11) DEFAULT NULL,
  `goldEarned` int(11) DEFAULT NULL,
  `goldSpent` int(11) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `lane` varchar(20) DEFAULT NULL,
  KEY `fk_MPD_matchId_idx` (`matchId`),
  CONSTRAINT `fk_MPD_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
