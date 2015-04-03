/*
MatchParticipantDetails_Extended
This table will hold more specific participant data
for each match recorded. There are a lot of stats. 
We thought it would be easier to split the stats into 
2 different tables

FK:
	fk_MPDe_matchId references MatchHeader
*/
CREATE TABLE `MatchParticipantDetails_Extended` (
  `matchId` int(11) NOT NULL,
  `teamId` int(11) DEFAULT NULL,
  `participantId` int(11) DEFAULT NULL,
  `minionsKilled` int(11) DEFAULT NULL,
  `neutralMinionsKilled` int(11) DEFAULT NULL,
  `championId` int(11) DEFAULT NULL,
  `neutralMinionsKilledTeamJungle` varchar(45) DEFAULT NULL,
  `neutralMinionsKilledEnemyJungle` int(11) DEFAULT NULL,
  `combatPlayerScore` int(11) DEFAULT NULL,
  `objectivePlayerScore` int(11) DEFAULT NULL,
  `totalPlayerScore` int(11) DEFAULT NULL,
  `totalScoreRank` int(11) DEFAULT NULL,
  `magicDamageDealtToChampions` int(11) DEFAULT NULL,
  `physicalDamageDealtToChampions` int(11) DEFAULT NULL,
  `trueDamageDealtToChampions` int(11) DEFAULT NULL,
  `visionWardsBoughtInGame` int(11) DEFAULT NULL,
  `sightWardsBoughtInGame` int(11) DEFAULT NULL,
  `largestKillingSpree` int(11) DEFAULT NULL,
  `magicDamageDealt` int(11) DEFAULT NULL,
  `physicalDamageDealt` int(11) DEFAULT NULL,
  `trueDamageDealt` int(10) unsigned zerofill DEFAULT NULL,
  `magicDamageTaken` int(11) DEFAULT NULL,
  `totalDamageTaken` int(11) DEFAULT NULL,
  `physicalDamageTaken` int(11) DEFAULT NULL,
  `trueDamageTaken` int(11) DEFAULT NULL,
  `firstBloodKill` int(11) DEFAULT NULL,
  `firstBloodAssist` int(11) DEFAULT NULL,
  `firstTowerKill` int(11) DEFAULT NULL,
  `firstTowerAssist` int(11) DEFAULT NULL,
  `firstInhibitorKill` int(11) DEFAULT NULL,
  `firstInhibitorAssist` int(11) DEFAULT NULL,
  `inhibitorKills` int(11) DEFAULT NULL,
  `towerKills` int(11) DEFAULT NULL,
  `wardsPlaced` int(11) DEFAULT NULL,
  `wardsKilled` int(11) DEFAULT NULL,
  `largestMultiKill` int(11) DEFAULT NULL,
  `killingSprees` int(11) DEFAULT NULL,
  `totalUnitsHealed` int(11) DEFAULT NULL,
  `totalTimeCrowdControlDealt` int(11) DEFAULT NULL,
  KEY `fk_MPDe_matchId_idx` (`matchId`),
  CONSTRAINT `fk_MPDe_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
