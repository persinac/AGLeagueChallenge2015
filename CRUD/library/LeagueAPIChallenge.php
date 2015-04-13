<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/07/15
 * Time: 9:50 AM
 */

class LeagueAPIChallenge {

    public $mys;
    var $all_matches = array();
    var $list_of_players = array();
    var $list_of_champions = array();
    var $more_skin_matches = array();
    var $less_skin_matches = array();
    var $equal_skin_matches = array();
    var $match_details = array();
    var $group_members = array();
    var $matches_won_more_skins = 0;
    var $matches_won_less_skins = 0;
    var $matches_won_equal_skins = 0;
    var $player_map = '';
    var $champ_map = '';

    var $player_nodes = '';
    var $player_links = '';
    var $temp_table = '';
    var $global_counter = 0;
    var $recursive_calls = 0;
    var $region = '';

    function __construct($reg, $host, $user, $pass, $database) {
        $this->region = $reg;
        $this->NewConnection($host, $user, $pass, $database);
    }

    /**
     * Connection functions
     */

    function NewConnection($host, $user, $pass, $database)
    {
        $this->mys = mysqli_connect($host, $user, $pass, $database);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }

    function CloseConnection()
    {
        try {
            mysqli_close($this->mys);
            return true;
        } catch (Exception $e) {
            printf("Close connection failed: %s\n", $this->mys->error);
        }
    }

    function insertIntoAPILog($url, $data, $whereDidIComeFrom) {
        $query = "INSERT INTO api_log VALUES(?,?,?,?,?)";
        $date = date("Y-m-d H:i:s");
        $id = $this->getMaxAPIID();
        $stmt = $this->mys->prepare($query);
        $stmt->bind_param('issss', $id, $url, $date, $data, $whereDidIComeFrom);
        if ($result = $stmt->execute()) {
            $stmt->close();
            $this->mys->commit();
            $retVal = 1;
        } else {
            $retVal = 0;
        }

        return $retVal;
    }

    function getMaxAPIID() {
        $query = "SELECT MAX(id) AS id FROM api_log;";
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $retVal = $row["id"] + 1;
            }
            $result->free();
        } else {
            $retVal = -1;
        }
        return $retVal;
    }

    function SetRegion($reg) {
        $this->region = $reg;
    }

    /** GETTERS **/

    function GetRegion() {
        return $this->region;
    }

    function GetMaxBucketId() {
        $retVal = -1;
        $query = 'select MAX(bucketId) AS max_id
                     from Buckets';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $retVal = $row["max_id"];
            }
            $result->free();
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    /** SETTERS **/

    /** INSERT/UPDATE/DELETE **/

    function InsertNewBucket($bucketId, $matchId, $region)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO Buckets VALUES(?,?,?)";
        if ($bucketId <= 1) {
            $retVal = 3;
        } else {
            if($matchId < 1) {
                $retVal = 4;
            } else {
                $stmt = $this->mys->prepare($query);
                $stmt->bind_param('iis', $bucketId, $matchId, $region);
                if ($result = $stmt->execute()) {
                    $stmt->close();
                    $this->mys->commit();
                    $retVal = 1;
                } else {
                    $retVal = 0;
                    $this->mys->rollback();
                }
            }
        }
        return $retVal;
    }

    function InsertIntoMatchHeader($bucketId, $matchId, $mapId, $region,
                                    $platformId, $matchMode, $matchType,
                                    $matchCreation, $matchDuration, $queueType,
                                    $season, $matchVersion)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchHeader VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
        if ($bucketId <= 1) {
            $retVal = 3;
        } else {
            if($matchId < 1) {
                $retVal = 4;
            } else {
                $stmt = $this->mys->prepare($query);
                $stmt->bind_param('iiissssiisss', $bucketId, $matchId, $mapId, $region
                                                , $platformId, $matchMode, $matchType
                                                , $matchCreation, $matchDuration, $queueType
                                                , $season, $matchVersion);
                if ($result = $stmt->execute()) {
                    $stmt->close();
                    $this->mys->commit();
                    $retVal = 1;
                } else {
                    $retVal = 0;
                    $this->mys->rollback();
                }
            }
        }
        return $retVal;
    }

    function InsertIntoMatchDetails($matchId, $frameInterval)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchDetails VALUES(?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('ii', $matchId, $frameInterval);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    function InsertIntoMatchParticipantDetails($matchId, $teamId, $participantId, $spell1Id
                                , $spell2Id, $championId, $highestAchievedSeasonTier, $champLevel
                                , $item0, $item1, $item2, $item3, $item4, $item5, $item6
                                , $kills, $doubleKills, $tripleKills, $quadraKills, $pentaKills
                                , $unrealKills, $largestKillingSpree, $deaths, $assists
                                , $totalDamageDealt, $totalDamageDealtToChampions, $totalDamageTaken
                                , $largestCriticalStrike, $totalHeal, $goldEarned, $goldSpent
                                , $role, $lane)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchParticipantDetails VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiisiiiiiiiiiiiiiiiiiiiiiiiiss', $matchId, $teamId, $participantId, $spell1Id
                , $spell2Id, $championId, $highestAchievedSeasonTier, $champLevel
                , $item0, $item1, $item2, $item3, $item4, $item5, $item6
                , $kills, $doubleKills, $tripleKills, $quadraKills, $pentaKills
                , $unrealKills, $largestKillingSpree, $deaths, $assists
                , $totalDamageDealt, $totalDamageDealtToChampions, $totalDamageTaken
                , $largestCriticalStrike, $totalHeal, $goldEarned, $goldSpent
                , $role, $lane);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    function InsertIntoMatchParticipantDetails_Extended($matchId,
                                                        $teamId,
                                                        $participantId,
                                                        $minionsKilled,
                                                        $neutralMinionsKilled,
                                                        $neutralMinionsKilledTeamJungle,
                                                        $neutralMinionsKilledEnemyJungle,
                                                        $combatPlayerScore,
                                                        $objectivePlayerScore,
                                                        $totalPlayerScore,
                                                        $totalScoreRank,
                                                        $magicDamageDealtToChampions,
                                                        $physicalDamageDealtToChampions,
                                                        $trueDamageDealtToChampions,
                                                        $visionWardsBoughtInGame,
                                                        $sightWardsBoughtInGame,
                                                        $magicDamageDealt,
                                                        $physicalDamageDealt,
                                                        $trueDamageDealt,
                                                        $magicDamageTaken,
                                                        $physicalDamageTaken,
                                                        $trueDamageTaken,
                                                        $firstBloodKill,
                                                        $firstBloodAssist,
                                                        $firstTowerKill,
                                                        $firstTowerAssist,
                                                        $firstInhibitorKill,
                                                        $firstInhibitorAssist,
                                                        $inhibitorKills,
                                                        $towerKills,
                                                        $wardsPlaced,
                                                        $wardsKilled,
                                                        $largestMultiKill,
                                                        $killingSprees,
                                                        $totalUnitsHealed,
                                                        $totalTimeCrowdControlDealt)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchParticipantDetails_Extended VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
                                                              ?,?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', $matchId,
                $teamId,
                $participantId,
                $minionsKilled,
                $neutralMinionsKilled,
                $neutralMinionsKilledTeamJungle,
                $neutralMinionsKilledEnemyJungle,
                $combatPlayerScore,
                $objectivePlayerScore,
                $totalPlayerScore,
                $totalScoreRank,
                $magicDamageDealtToChampions,
                $physicalDamageDealtToChampions,
                $trueDamageDealtToChampions,
                $visionWardsBoughtInGame,
                $sightWardsBoughtInGame,
                $magicDamageDealt,
                $physicalDamageDealt,
                $trueDamageDealt,
                $magicDamageTaken,
                $physicalDamageTaken,
                $trueDamageTaken,
                $firstBloodKill,
                $firstBloodAssist,
                $firstTowerKill,
                $firstTowerAssist,
                $firstInhibitorKill,
                $firstInhibitorAssist,
                $inhibitorKills,
                $towerKills,
                $wardsPlaced,
                $wardsKilled,
                $largestMultiKill,
                $killingSprees,
                $totalUnitsHealed,
                $totalTimeCrowdControlDealt);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    function InsertIntoMatchTeamDetails($matchId,
                                        $teamId,
                                        $baronKills,
                                        $dragonKills,
                                        $totalKills,
                                        $totalAssists,
                                        $totalDeaths,
                                        $totalGoldEarned,
                                        $totalGoldSpent,
                                        $winner,
                                        $firstBlood,
                                        $firstTower,
                                        $firstInhibitor,
                                        $firstBaron,
                                        $firstDragon,
                                        $vilemawKills)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchTeamDetails VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiiiiiiiiiiii', $matchId,
                $teamId,
                $baronKills,
                $dragonKills,
                $totalKills,
                $totalAssists,
                $totalDeaths,
                $totalGoldEarned,
                $totalGoldSpent,
                $winner,
                $firstBlood,
                $firstTower,
                $firstInhibitor,
                $firstBaron,
                $firstDragon,
                $vilemawKills);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    function InsertIntoMatchBans($matchId,
                                        $firstBan,
                                        $secondBan,
                                        $thirdBan,
                                        $fourthBan,
                                        $fifthBan,
                                        $sixthBan)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchBans VALUES(?,?,?,?,?,?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiii', $matchId,
                $firstBan,
                $secondBan,
                $thirdBan,
                $fourthBan,
                $fifthBan,
                $sixthBan);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    function InsertIntoMatchEvents($matchId,
                                 $eventType,
                                 $timestamp,
                                 $skillSlot,
                                 $participantId,
                                 $levelUpType,
                                 $itemId,
                                 $creatorId,
                                 $wardType, $killerId,$victimId,$assistingParticipants,$pos_x, $pos_y,
                                 $teamId, $laneType, $buildingType, $towerType)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchEvents VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('isiiisiisiisiiisss', $matchId,
                $eventType,
                $timestamp,
                $skillSlot,
                $participantId,
                $levelUpType,
                $itemId,
                $creatorId,
                $wardType, $killerId,$victimId,$assistingParticipants,$pos_x, $pos_y,
                $teamId, $laneType, $buildingType, $towerType);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    function InsertIntoMatchParticipantTimeline($matchId,
                                                $participantId,
                                                $pos_x,
                                                $pos_y,
                                                $currentGold,
                                                $totalGold,
                                                $level,
                                                $xp,
                                                $minionsKilled,
                                                $jungleMinionsKilled,
                                                $dominionScore,
                                                $teamScore,
                                                $timestamp)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchParticipantTimeline VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiiiiiiiii', $matchId,
                $participantId,
                $pos_x,
                $pos_y,
                $currentGold,
                $totalGold,
                $level,
                $xp,
                $minionsKilled,
                $jungleMinionsKilled,
                $dominionScore,
                $teamScore,
                $timestamp);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    function InsertIntoMatchParticipantDeltas($matchId,
                                              $teamId,
                                              $participantId,
                                              $creepsPerMin_zeroToTen,
                                              $creepsPerMin_TenToTwenty,
                                              $creepsPerMin_TwentyToThirty,
                                              $creepsPerMin_ThirtyToEnd,
                                              $xpPerMin_zeroToTen,
                                              $xpPerMin_TenToTwenty,
                                              $xpPerMin_TwentyToThirty,
                                              $xpPerMin_ThirtyToEnd,
                                              $goldPerMin_zeroToTen,
                                              $goldPerMin_TenToTwenty,
                                              $goldPerMin_TwentyToThirty,
                                              $goldPerMin_ThirtyToEnd,
                                              $xpDiffPerMin_zeroToTen,
                                              $xpDiffPerMin_TenToTwenty,
                                              $xpDiffPerMin_TwentyToThirty,
                                              $xpDiffPerMin_ThirtyToEnd,
                                              $damageTakenPerMin_zeroToTen,
                                              $damageTakenPerMin_TenToTwenty,
                                              $damageTakenPerMin_TwentyToThirty,
                                              $damageTakenPerMin_ThirtyToEnd,
                                              $damageTakenDiffPerMin_zeroToTen,
                                              $damageTakenDiffPerMin_TenToTwenty,
                                              $damageTakenDiffPerMin_TwentyToThirty,
                                              $damageTakenDiffPerMin_ThirtyToEnd,
                                              $csDiffPerMin_zeroToTen,
                                              $csDiffPerMin_tenToTwenty,
                                              $csDiffPerMin_twentyToThrity,
                                              $csDiffPerMin_thirtyToEnd)
    {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "INSERT INTO MatchParticipantDeltaValues VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', $matchId,
                $teamId,
                $participantId,
                $creepsPerMin_zeroToTen,
                $creepsPerMin_TenToTwenty,
                $creepsPerMin_TwentyToThirty,
                $creepsPerMin_ThirtyToEnd,
                $xpPerMin_zeroToTen,
                $xpPerMin_TenToTwenty,
                $xpPerMin_TwentyToThirty,
                $xpPerMin_ThirtyToEnd,
                $goldPerMin_zeroToTen,
                $goldPerMin_TenToTwenty,
                $goldPerMin_TwentyToThirty,
                $goldPerMin_ThirtyToEnd,
                $xpDiffPerMin_zeroToTen,
                $xpDiffPerMin_TenToTwenty,
                $xpDiffPerMin_TwentyToThirty,
                $xpDiffPerMin_ThirtyToEnd,
                $damageTakenPerMin_zeroToTen,
                $damageTakenPerMin_TenToTwenty,
                $damageTakenPerMin_TwentyToThirty,
                $damageTakenPerMin_ThirtyToEnd,
                $damageTakenDiffPerMin_zeroToTen,
                $damageTakenDiffPerMin_TenToTwenty,
                $damageTakenDiffPerMin_TwentyToThirty,
                $damageTakenDiffPerMin_ThirtyToEnd,
                $csDiffPerMin_zeroToTen,
                $csDiffPerMin_tenToTwenty,
                $csDiffPerMin_twentyToThrity,
                $csDiffPerMin_thirtyToEnd);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $this->mys->commit();
                $retVal = 1;
            } else {
                $retVal = 0;
                $this->mys->rollback();
            }
        }
        return $retVal;
    }

    /* READ OPERATIONS */

    function GetFirstPageStats() {
        $match_array = array();
        $query = "select * from MatchHeader ORDER BY bucketId";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('bucketId' => '', 'matchId' => '', 'region' => '',
                    'matchCreation' => '', 'matchDuration' => '', 'queueType' => '');
                $detail->bucketId = $row['bucketId'];
                $detail->matchId = $row['matchId'];
                $detail->region = $row["region"];
                $detail->matchCreation = $row["matchCreation"];
                $detail->matchDuration = $row["matchDuration"];
                $detail->queueType = $row["queueType"];
                $match_array[] = $detail;
            }
            $result->free();
        }
        return $match_array;
    }


    //region MINION FUNCTIONS
    function GetMinionKills() {
        $minionsKilled = array();
        $query = "select SUM(minionsKilled) AS 'minionsKilled' from MatchParticipantDetails_Extended";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $minionsKilled = $row["minionsKilled"];
            }
            $result->free();
        }
        return $minionsKilled;
    }

    function GetNeutralMinionKills() {
        $minionsKilled = array();
        $query = "select SUM(neutralMinionsKilled) AS 'minionsKilled' from MatchParticipantDetails_Extended";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $minionsKilled = $row["minionsKilled"];
            }
            $result->free();
        }
        return $minionsKilled;
    }
    //endregion

    //region URGOT READ FUNCTIONS
    /**
     * Urgot read functions
     */

    function GetNumOfTimeUrgotPicked() {
        $urgotPicked = -1;
        $query = "select COUNT(*) AS 'urgotPicked' from MatchParticipantDetails where championid = 6";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $urgotPicked = $row["urgotPicked"];
            }
            $result->free();
        }
        return $urgotPicked;
    }

    function GetNumOfTimeUrgotBanned() {
        $urgotBanned = -1;
        $query = "select COUNT(*) AS 'urgotBanned' from MatchHeader mh
                  JOIN MatchDetails md ON mh.matchId = md.matchId
                  JOIN MatchBans mb ON  mh.matchId = mb.matchId
                    where mh.bucketId > 1
                  AND (mb.firstBan = 6 OR mb.secondBan = 6 OR mb.thirdBan = 6 OR mb.fourthBan = 6 OR mb.fifthBan = 6 OR mb.sixthBan = 6)";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $urgotBanned = $row["urgotBanned"];
            }
            $result->free();
        }
        return $urgotBanned;
    }

    function GetNumOfTimeUrgotWon() {
        $urgotWins = -1;
        $query = "select COUNT(*) AS 'urgotWins' from MatchParticipantDetails mpd
                  JOIN MatchTeamDetails mtd ON mpd.matchId = mtd.matchId
	                                          AND mpd.teamId = mtd.teamId
                  where championid = 6
                    AND mtd.winner = 1";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $urgotWins = $row["urgotWins"];
            }
            $result->free();
        }
        return $urgotWins;
    }
    //endregion

}

class TimeChecker extends LeagueAPIChallenge {

    function __construct($reg, $host, $user, $pass, $database) {
        $this->region = $reg;
        parent::__construct($reg, $host, $user, $pass, $database);
    }

    function GetTime()
    {
        $this->mys->autocommit(TRUE);
        $time = new stdClass();
        $query = "select hour, firstHalf, secondHalf from TimeCheck
                    where (firstHalf = 0 OR secondHalf = 0)
                    ORDER BY hour
                    LIMIT 1;";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $time->hour = $row['hour'];
                $time->firstHalf = $row['firstHalf'];
                $time->secondHalf = $row['secondHalf'];
            }
            $result->free();
        }
        return $time;
    }

    function GetTimeToUse() {
        $retVal = new stdClass();

        $val = $this->GetTime();
        $retVal->hour = $val->hour;
        if($val->firstHalf == 0) {
            $retVal->minute = "0";
            $retVal->updateRetVal = $this->UpdateTimeTable($val->hour, 1, 0);
        } else {
            $retVal->minute = "30";
            $retVal->updateRetVal = $this->UpdateTimeTable($val->hour, 1, 1);
        }
        return $retVal;
    }

    function UpdateTimeTable($hour, $firstHalf, $secondHalf) {
        $retVal = new stdClass();
        $this->mys->autocommit(FALSE);
        $query = "update TimeCheck
                    set firstHalf = ?, secondHalf = ?
                    where hour = ?;";

        $this->mys->next_result();
        $stmt = $this->mys->prepare($query);
        $stmt->bind_param('iii', $firstHalf, $secondHalf, $hour);
        if ($result = $stmt->execute()) {
            $stmt->close();
            $this->mys->next_result();
            $retVal->insertVal = $this->insertIntoAPILog("NONE", "HOUR: $hour, FirstHour: $firstHalf, SECOND: $secondHalf", "UpdateTimeTable()");
            $retVal->updateVal = 1;
            $this->mys->commit();
        } else {
            $retVal->updateVal = 0;
            $retVal->insertVal = $this->insertIntoAPILog("NONE", "UPDATE TIMETABLE FAIL", "UpdateTimeTable()");
            $this->mys->rollback();
            $this->mys->next_result();

        }
        $this->mys->autocommit(TRUE);
        return $retVal;
    }

    function ResetTimeTable() {
        $retVal = -1;
        $this->mys->autocommit(FALSE);
        $query = "update TimeCheck
                    set firstHalf = 0, secondHalf = 0;";

        $this->mys->next_result();
        $stmt = $this->mys->prepare($query);
        if ($result = $stmt->execute()) {
            $stmt->close();

            $this->insertIntoAPILog("NONE", "RESET TIMETABLE SUCCESS", "ResetTimeTable()");
            $retVal = 1;
            $this->mys->commit();
        } else {
            $retVal = 0;

            $this->insertIntoAPILog("NONE", "RESET TIMETABLE FAIL", "ResetTimeTable()");
            $this->mys->rollback();
        }
        $this->mys->autocommit(TRUE);
        return $retVal;
    }
}