/*
MatchParticipantDeltaValues
Holds delta time values for teams and speicific participants.
Values it holds:
	- Creeps
	- XP
	- Gold
    - XP Diff
    - Damage Taken
    - Damage Taken Diff

Time intervals:
	- 0-10
    - 10-20
    - 20-30
    - 30-40
    - 40-50
    - 50-60
    - Remainder
Only chose to hold up to one hour of values because most URF games
 do not make it past 30 minutes
However, we chose to add in a remainder in case there are games that last longer
than 60 minutes

FK:
	fk_MPDV_matchId references MatchHeader matchId
*/
CREATE TABLE `MatchParticipantDeltaValues` (
  `matchId` int(11) NOT NULL,
  `teamId` int(11) NOT NULL,
  `participantId` int(11) NOT NULL,
  `creepsPerMin_zeroToTen` decimal(30,15) DEFAULT NULL,
  `creepsPerMin_TenToTwenty` decimal(30,15) DEFAULT NULL,
  `creepsPerMin_TwentyToThirty` decimal(30,15) DEFAULT NULL,
  `creepsPerMin_ThirtyToFourty` decimal(30,15) DEFAULT NULL,
  `creepsPerMin_FourtyToFifty` decimal(30,15) DEFAULT NULL,
  `creepsPerMin_FiftyToSixty` decimal(30,15) DEFAULT NULL,
  `creepsPerMin_Remainder` decimal(30,15) DEFAULT NULL,
  `xpPerMin_zeroToTen` decimal(30,15) DEFAULT NULL,
  `xpPerMin_TenToTwenty` decimal(30,15) DEFAULT NULL,
  `xpPerMin_TwentyToThirty` decimal(30,15) DEFAULT NULL,
  `xpPerMin_ThirtyToFourty` decimal(30,15) DEFAULT NULL,
  `xpPerMin_FourtyToFifty` decimal(30,15) DEFAULT NULL,
  `xpPerMin_FiftyToSixty` decimal(30,15) DEFAULT NULL,
  `xpPerMin_Remainder` decimal(30,15) DEFAULT NULL,
  `goldPerMin_zeroToTen` decimal(30,15) DEFAULT NULL,
  `goldPerMin_TenToTwenty` decimal(30,15) DEFAULT NULL,
  `goldPerMin_TwentyToThirty` decimal(30,15) DEFAULT NULL,
  `goldPerMin_ThirtyToFourty` decimal(30,15) DEFAULT NULL,
  `goldPerMin_FourtyToFifty` decimal(30,15) DEFAULT NULL,
  `goldPerMin_FiftyToSixty` decimal(30,15) DEFAULT NULL,
  `goldPerMin_Remainder` decimal(30,15) DEFAULT NULL,
  `xpDiffPerMin_zeroToTen` decimal(30,15) DEFAULT NULL,
  `xpDiffPerMin_TenToTwenty` decimal(30,15) DEFAULT NULL,
  `xpDiffPerMin_TwentyToThirty` decimal(30,15) DEFAULT NULL,
  `xpDiffPerMin_ThirtyToFourty` decimal(30,15) DEFAULT NULL,
  `xpDiffPerMin_FourtyToFifty` decimal(30,15) DEFAULT NULL,
  `xpDiffPerMin_FiftyToSixty` decimal(30,15) DEFAULT NULL,
  `xpDiffPerMin_Remainder` decimal(30,15) DEFAULT NULL,
  `damageTakenPerMin_zeroToTen` decimal(30,15) DEFAULT NULL,
  `damageTakenPerMin_TenToTwenty` decimal(30,15) DEFAULT NULL,
  `damageTakenPerMin_TwentyToThirty` decimal(30,15) DEFAULT NULL,
  `damageTakenPerMin_ThirtyToFourty` decimal(30,15) DEFAULT NULL,
  `damageTakenPerMin_FourtyToFifty` decimal(30,15) DEFAULT NULL,
  `damageTakenPerMin_FiftyToSixty` decimal(30,15) DEFAULT NULL,
  `damageTakenPerMin_Remainder` decimal(30,15) DEFAULT NULL,
  `damageTakenDiffPerMin_zeroToTen` decimal(30,15) DEFAULT NULL,
  `damageTakenDiffPerMin_TenToTwenty` decimal(30,15) DEFAULT NULL,
  `damageTakenDiffPerMin_TwentyToThirty` decimal(30,15) DEFAULT NULL,
  `damageTakenDiffPerMin_ThirtyToFourty` decimal(30,15) DEFAULT NULL,
  `damageTakenDiffPerMin_FourtyToFifty` decimal(30,15) DEFAULT NULL,
  `damageTakenDiffPerMin_FiftyToSixty` decimal(30,15) DEFAULT NULL,
  `damageTakenDiffPerMin_Remainder` decimal(30,15) DEFAULT NULL,
  KEY `fk_MPT_matchId_idx` (`matchId`),
  CONSTRAINT `fk_MPDV_matchId` FOREIGN KEY (`matchId`) REFERENCES `MatchHeader` (`matchId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
