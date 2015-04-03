/*
This table - Buckets - will be filled with MatchIDs from the call to ChallengeAPI vX.n
It will drive MatchHeader table.
*/
CREATE TABLE `Buckets` (
  `bucketId` int(11) NOT NULL,
  `matchId` int(11) NOT NULL,
  `region` varchar(20) DEFAULT '-',
  PRIMARY KEY (`bucketId`,`matchId`),
  KEY `fk_matchId_idx` (`matchId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
