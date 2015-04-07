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


    /** GETTERS **/

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
        $query = "INSERT INTO MatchDetails VALUES(?,?)";
        if ($matchId <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiisiiiiiiiiiiiiiiiiiiiiiiiiii', $matchId, $teamId, $participantId, $spell1Id
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
}