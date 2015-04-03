/*
MatchHeader
This table will hold main match characteristics. It will
also drive all other tables except for Buckets. Some of this 
information may be irrelevant, but I will store it just 
in case

PK:
	matchId

FK:
	fk_bucketId references Buckets bucketId
*/
CREATE TABLE `MatchHeader` (
  `bucketId` int(11) NOT NULL,
  `matchId` int(11) NOT NULL DEFAULT '0',
  `mapId` int(11) NOT NULL DEFAULT '0',
  `region` varchar(20) DEFAULT NULL,
  `platformId` varchar(20) DEFAULT NULL,
  `matchMode` varchar(50) DEFAULT NULL,
  `matchType` varchar(50) DEFAULT NULL,
  `matchCreation` int(13) DEFAULT NULL,
  `matchDuration` int(11) DEFAULT '0',
  `queueType` varchar(50) DEFAULT NULL,
  `season` varchar(20) DEFAULT NULL,
  `matchVersion` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`matchId`),
  KEY `fk_bucketId_idx` (`bucketId`),
  KEY `fk_matchId_idx` (`matchId`),
  CONSTRAINT `fk_bucketId` FOREIGN KEY (`bucketId`) REFERENCES `Buckets` (`bucketId`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
