<?php
require_once('../../Connections/lol_api_challenge_conn.php');
require_once('../../keys/key.php');
?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/12/14
 * Time: 9:16 AM
 */

include('../../CRUD/library/Champion.php');
include('../../CRUD/library/Team.php');

/**
 * Class riot_api
 * Used to make calls to riot api and parse the data
 * We're trying not to perform any of OUR CRUD operations in any of
 * these classes.
 */
class riot_api {
    var $key = $my_key;
    var $region = '';
    var $url_https = 'https://';
    var $url_prefix = '.api.pvp.net/api/lol/';
    public $mys;

    function __construct($reg, $host, $user, $pass, $database) {
        $this->region = $reg;
        $this->NewConnection($host, $user, $pass, $database);
    }

    function NewConnection($host, $user, $pass, $database) {
        $this->mys = mysqli_connect($host, $user, $pass, $database);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }

    function CloseConnection() {
        try {
            mysqli_close($this->mys);
            return true;
        } catch (Exception $e) {
            printf("Close connection failed: %s\n", $this->mys->error);
        }
    }

    function MakeCURLCall($url_to_exec, $whereDidIComeFrom = "") {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url_to_exec
        ));
        $result = curl_exec($ch);
        $this->insertIntoAPILog($url_to_exec, "KEY: ". $this->key ." :: ".substr($result, 0, 800), $whereDidIComeFrom);
        curl_close($ch);
        return $result;
    }

    /* SETTERS */
    function SetRegion($reg) {
        $this->region = $reg;
    }

    /* GETTERS */
    function GetRegion() {
        return $this->region;
    }

    function GetKey() {
        return $this->key;
    }

    function GetURLPre() {
        return $this->url_https . $this->GetRegion() . $this->url_prefix;
    }

    function insertIntoAPILog($url, $data, $whereDidIComeFrom) {
        $query = "INSERT INTO api_log VALUES(?,?,?,?,?)";
        $date = date("Y-m-d H:i:s");
        $id = $this->getMaxAPIID();
        $stmt = $this->mys->prepare($query);
        $stmt->bind_param('issss', $id, $url, $date, $data, $whereDidIComeFrom);
        if ($result = $stmt->execute()) {
            $stmt->close();
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

}

class Buckets extends riot_api {
    var $region = '';
    var $challenge_api_version = '4.1';
    var $beginDate = -1;
    var $url_bucket = '';

    function __construct($reg, $key, $host, $user, $pass, $database) {
        $this->region = $reg;
        $this->key = $key;
        parent::__construct($reg, $host, $user, $pass, $database);
    }

    function TestingOutKeyLocation() {
        return $this->key;
    }

    function GetBucketOfMatches() {
        $url_postfix = $this->GetRegion() . '/v'.$this->challenge_api_version.'/game/ids?beginDate='.$this->beginDate.'&api_key=' . $this->GetKey();
        $this->SetBucketURL($url_postfix);
        return $this->MakeCURLCall($this->url_bucket, "Buckets - GetBucketOfMatches()");
    }

    function SetBeginDate($beginDate) {
        $this->beginDate = $beginDate;
    }

    function SetBucketURL($data) {
        $this->url_bucket = $this->GetURLPre() . '' . $data;
    }

    function GetBeginDate() {
        return $this->beginDate;
    }

    function GetBucketURL() {
        return $this->url_bucket;
    }
}

class LeagueChampions extends riot_api {
    var $url_champ = '';
    var $champ_version = '1.2';

    function __construct($reg, $host, $user, $pass, $database) {
        parent::__construct($reg, $host, $user, $pass, $database);
    }

    /* PRIMARY FUNCTION(S) */
    function PrintAllChampions() {
        $url_postfix = 'static-data/' . $this->GetRegion() . '/v'.$this->champ_version.'/champion?api_key=' . $this->GetKey();
        $this->SetChampionURL($url_postfix);
        return $this->MakeCURLCall($this->url_champ, "LeagueChampions - PrintAllChampions()");
    }

    /*  */
    function GetChampionByID($id) {
        $query = "SELECT * FROM champions WHERE champ_id = $id";
        $det = (object) array('id'=>'-1', 'name'=>'DEFAULT');

        if ($result = $this->mys->query($query)) {

            while ($row = $result->fetch_assoc()) {

                $det->id = $row["champ_id"];
                $det->name = $row["champ_name"];
            }
            $result->free();
        }

        return $det;
    }

    function apiGetChampionByID($id) {

        $url_postfix = 'static-data/' . $this->GetRegion() . '/v1.2/champion/'.$id.'?api_key=' . $this->GetKey();
        $this->SetChampionURL($url_postfix);

        return $this->MakeCURLCall($this->url_champ, "LeagueChampions - apiGetChampionByID( $id )");
    }

    function SetChampionURL($data) {
        $this->url_champ = $this->GetURLPre() . '' . $data;

    }
}

class SummonerInfo extends riot_api {

    var $summoner_id = 0;
    var $summoner_name = '';
    var $url_summoner = '';
    var $summoner_version = '1.4';

    function __construct($reg, $host, $user, $pass, $database) {
        parent::__construct($reg, $host, $user, $pass, $database);
    }

    /* PRIMARY FUNCTION(S) */
    function SearchForSummonerByName($name) {
        $name = str_replace(' ', '%20', $name);
        $url_postfix = 'by-name/' . $name;
        $this->SetSummonerURL($url_postfix);
        return $this->MakeCURLCall($this->url_summoner, "SummonerInfo - SearchForSummonerByName( $name )");
    }

    function SearchForSummonerByID($id, $cron = 0) {
        $this->SetSummonerURL($id);
        $string = "";
        if($cron > 0) {
            $string = "CRONJOB: SummonerInfo - SearchForSummonerByID( $id )";
        } else {
            $string = "SummonerInfo - SearchForSummonerByID( $id )";
        }
        return $this->MakeCURLCall($this->url_summoner, $string);
    }

    /* SETTERS */

    /*
     *
     */
    function SetSummonerURL($data) {
        $this->url_summoner = $this->GetURLPre() . '' . $this->GetRegion() . '/';
        $this->url_summoner .= 'v'.$this->summoner_version.'/summoner/' . $data . '?api_key=' . $this->GetKey();
    }

    function SetSummonerName($name) {
        $this->summoner_name = $name;
    }

    function SetSummonerID($id) {
        $this->summoner_id = $id;
    }

    /* GETTERS */
    function GetSummonerName() {
        return $this->summoner_name;
    }

    function GetSummonerID() {
        return $this->summoner_id;
    }
}

class LeagueGames extends riot_api {

    var $game_id = 0;
    var $url_game = '';
    var $teams = '';
    var $game_version = "1.3";

    function __construct($reg, $host, $user, $pass, $database) {
        parent::__construct($reg, $host, $user, $pass, $database);
    }

    function GetSummonerRecentGames($summoner_id) {
        $url_postfix = 'by-summoner/' . $summoner_id . '/recent?api_key=' . $this->GetKey();
        $this->SetSummonerURL($url_postfix);

        return $this->MakeCURLCall($this->url_game, "LeagueGames - GetSummonerRecentGames( $summoner_id )");
    }

    /* SETTERS */

    /*
     *
     */
    function SetSummonerURL($data) {
        $this->url_game = $this->GetURLPre() . '' . $this->GetRegion() . '/';
        $this->url_game .= 'v'.$this->game_version.'/game/' . $data;
    }

    function SetTeam($data) {
        $this->teams = $data;
    }
}

class LeagueMatchDetails extends LeagueGames {
    var $matchid = 0;
    var $url_match = '';
    var $match_version = '2.2';

    function __construct($mid, $region, $key, $host, $user, $pass, $database) {
        $this->matchid = $mid;
        $this->key = $key;
        parent::__construct($region, $host, $user, $pass, $database);
    }



    /* SETTERS */

    /*
     *
     */
    function SetMatchURL($data) {
        $this->url_match = $this->GetURLPre() . '' . $this->GetRegion() . '/';
        $this->url_match .= 'v'.$this->match_version.'/match/' . $data;

    }

    /********* GETTERS ************/

    /*
     * Gets match details by matchid
     * Matchid = gameid from class LeagueGames - passed via JS in an onclick call
     * - see api_interface for reference
     */
    function GetMatchDetails($isBackground = 0) {
        $url_postfix = $this->matchid . '?includeTimeline=true&api_key=' . $this->GetKey();
        $this->SetMatchURL($url_postfix);
        if($isBackground > 0) {
            $stringForApiLog = "BACKGROUND PROCESS: LeagueMatchDetails - GetMatchDetails()";
        } else {
            $stringForApiLog = "LeagueMatchDetails - GetMatchDetails()";
        }

        return $this->MakeCURLCall($this->url_match , $stringForApiLog);
    }

    function GetBasicParticipantDetails($arr) {
        $t_arr = array();
        $testing = array();
        $string = "";
        foreach($arr AS $i=>$val) {
            if($i == 'participants') {
                if (is_array($val)) {
                    for ($j = 0; $j < 10; $j++) {
                        $det = (object) array('pID'=>'','cID'=>'','tID'=>'','role'=>'','lane'=>'');
                        $det->pID = $val[$j]->participantId;
                        $det->cID = $val[$j]->championId;
                        $det->tID = $val[$j]->teamId;
                        $det->role = $val[$j]->timeline->role;
                        $det->lane = $val[$j]->timeline->lane;
                        $t_arr[] = $det;
                    }
                }
            }
        }
        return $t_arr;
    }

    /**
     * @param $arr - the array of unorganized champions
     * @param $arr_2 - the array of data returned by GetMatchDetails()
     * @return array - the array of unorganized champions...but now has stats
     */
    function GetParticipantMatchStats(&$arr, $arr_2, $league_obj) {
        $string = "";
        foreach($arr_2 AS $i=>$val) {
            if($i == 'participants') {
                if (is_array($val)) {
                    for ($j = 0; $j < 10; $j++) {
                        for($k = 0; $k < sizeof($arr); $k++) {
                            if($arr[$k]->team == $val[$j]->teamId
                                && $arr[$k]->champID == $val[$j]->championId) {
                                $arr[$k]->SetName($league_obj->getChampName($arr[$k]->champID));
                                $arr[$k]->SetKills($val[$j]->stats->kills);
                                $arr[$k]->SetAssists($val[$j]->stats->assists);
                                $arr[$k]->SetDeaths($val[$j]->stats->deaths);
                                $arr[$k]->SetCS($val[$j]->stats->minionsKilled);
                                $arr[$k]->SetGoldEarned($val[$j]->stats->goldEarned);
                                $arr[$k]->SetGoldSpent($val[$j]->stats->goldSpent);
                            }
                        }
                    }
                }
            }
        }
       echo $string;
    }

    function ParticipantDetails($arr) {
        $testing = array();
        foreach($arr AS $i=>$val) {
            if($i == 'participants') {
                if (is_array($val)) {
                    for ($j = 0; $j < 10; $j++) {
                        $champion = new Champion($val[$j]->teamId, $val[$j]->participantId,
                            $val[$j]->championId, $val[$j]->stats->kills, $val[$j]->stats->assists,
                            $val[$j]->stats->deaths, $val[$j]->stats->goldEarned, $val[$j]->stats->goldSpent,
                            $val[$j]->stats->champLevel,$val[$j]->stats->firstBloodAssist, $val[$j]->stats->firstBloodKill,
                            $val[$j]->stats->firstInhibitorKill, $val[$j]->stats->firstInhibitorAssist,
                            $val[$j]->stats->firstTowerKill, $val[$j]->stats->firstTowerAssist,
                            $val[$j]->stats->inhibitorKills, $val[$j]->stats->killingSprees,
                            $val[$j]->stats->largestKillingSpree, $val[$j]->stats->largestCriticalStrike,
                            $val[$j]->stats->towerKills, $val[$j]->stats->doubleKills, $val[$j]->stats->tripleKills,
                            $val[$j]->stats->quadraKills, $val[$j]->stats->pentaKills, $val[$j]->stats->unrealKills,
                            $val[$j]->highestAchievedSeasonTier, $val[$j]->spell1Id, $val[$j]->spell2Id,
                            $val[$j]->stats->item0, $val[$j]->stats->item1, $val[$j]->stats->item2,
                            $val[$j]->stats->item3, $val[$j]->stats->item4, $val[$j]->stats->item5,
                            $val[$j]->stats->item6, $val[$j]->stats->totalDamageDealt,
                            $val[$j]->stats->totalDamageDealtToChampions, $val[$j]->stats->totalDamageTaken,
                            $val[$j]->stats->totalHeal, $val[$j]->timeline->role, $val[$j]->timeline->lane,
                            $val[$j]->stats->minionsKilled, $val[$j]->stats->neutralMinionsKilled,
                            $val[$j]->stats->neutralMinionsKilledTeamJungle, $val[$j]->stats->neutralMinionsKilledEnemyJungle,
                            $val[$j]->stats->combatPlayerScore, $val[$j]->stats->objectivePlayerScore,
                            $val[$j]->stats->totalPlayerScore, $val[$j]->stats->totalScoreRank,
                            $val[$j]->stats->magicDamageDealtToChampions, $val[$j]->stats->physicalDamageDealtToChampions,
                            $val[$j]->stats->trueDamageDealtToChampions, $val[$j]->stats->visionWardsBoughtInGame,
                            $val[$j]->stats->sightWardsBoughtInGame,
                            $val[$j]->stats->magicDamageDealt, $val[$j]->stats->physicalDamageDealt,
                            $val[$j]->stats->trueDamageDealt, $val[$j]->stats->magicDamageTaken,
                            $val[$j]->stats->physicalDamageTaken, $val[$j]->stats->trueDamageTaken,
                            $val[$j]->stats->towerKills, $val[$j]->stats->wardsKilled,
                            $val[$j]->stats->wardsPlaced, $val[$j]->stats->killingSprees,
                            $val[$j]->stats->largestMultiKill, $val[$j]->stats->totalUnitsHealed,
                            $val[$j]->stats->totalTimeCrowdControlDealt);
                        $testing[] = $champion;
                        $champion = null;
                    }
                }
            }
        }
        return $testing;
    }

    function TeamDetails($arr) {
        $team_arr = array();
        foreach($arr AS $i=>$val) {
            if($i == 'teams') {
                if (is_array($val)) {
                    for ($j = 0; $j < 3; $j++) {
                        if($val[$j]->teamId == 100) {
                            $team1 = new Team($val[$j]->baronKills, $val[$j]->dragonKills,
                                $val[$j]->firstBaron, $val[$j]->firstBlood, $val[$j]->firstDragon,
                                $val[$j]->firstInhibitor, $val[$j]->firstTower, $val[$j]->inhibitorKills,
                                $val[$j]->teamId, $val[$j]->towerKills, $val[$j]->vilemawKills, $val[$j]->winner,
                                0,0,0,0,0);
                            $team_arr[] = $team1;
                        } else  if($val[$j]->teamId == 200) {
                            $team2 = new Team($val[$j]->baronKills, $val[$j]->dragonKills,
                                $val[$j]->firstBaron, $val[$j]->firstBlood, $val[$j]->firstDragon,
                                $val[$j]->firstInhibitor, $val[$j]->firstTower, $val[$j]->inhibitorKills,
                                $val[$j]->teamId, $val[$j]->towerKills, $val[$j]->vilemawKills, $val[$j]->winner,
                                0,0,0,0,0);
                            $team_arr[] = $team2;
                        }
                    }
                }
            }
        }
        return $team_arr;
    }

    function MatchBans($arr, $matchId = 0) {
        $bans = (object)array('matchId' => $matchId, 'first' => '', 'second' => ''
        , 'third' => '', 'fourth' => '', 'fifth' => '', 'sixth' => '');
        foreach($arr AS $i=>$val) {
            if($i == 'teams') {
                if (is_array($val)) {
                    for ($j = 0; $j < 3; $j++) {
                        if ($val[$j]->teamId == 100) {
                            $bans->first = $val[$j]->bans[0]->championId;
                            $bans->third = $val[$j]->bans[1]->championId;
                            $bans->fifth = $val[$j]->bans[2]->championId;
                        } else if ($val[$j]->teamId == 200) {
                            $bans->second = $val[$j]->bans[0]->championId;
                            $bans->fourth = $val[$j]->bans[1]->championId;
                            $bans->sixth = $val[$j]->bans[2]->championId;
                        }
                    }
                }
            }
        }
        return $bans;
    }

    function MatchEvents($arr) {
        $event_arr = array();
        foreach($arr AS $i=>$val) {
            if($i == 'timeline') {
                for($i = 1; $i < sizeof($val->frames); $i++) {
                    foreach($val->frames[$i]->events AS $event) {
                        $event_obj = (object)array('eventType' => '',
                            'timestamp' => '',
                            'skillSlot' => '',
                            'participantId' => '',
                            'levelUpType' => '',
                            'itemId' => '',
                            'creatorId' => '',
                            'wardType' => '',
                            'killerId' => '',
                            'victimId' => '',
                            'assistingParticipantIds' => '',
                            'pos_x' => '',
                            'pos_y' => '',
                            'teamId' => '',
                            'laneType' => '',
                            'buildingType' => '',
                            'towerType' => '');
                        //echo "EVENT TYPE: " . $event->eventType . ", ";
                        $assistParticipants = "";
                        $event_obj->eventType = $event->eventType;
                        $event_obj->timestamp = $event->timestamp;
                        if($event->eventType == 'SKILL_LEVEL_UP') {
                            $event_obj->skillSlot = $event->skillSlot;
                            $event_obj->participantId = $event->participantId;
                            $event_obj->levelUpType = $event->levelUpType;
                        } else if($event->eventType == 'ITEM_PURCHASED') {
                            $event_obj->itemId = $event->itemId;
                            $event_obj->participantId = $event->participantId;
                        } else if($event->eventType == 'ITEM_UNDO') {
                            $event_obj->itemId = $event->itemId;
                            $event_obj->participantId = $event->participantId;
                        } else if($event->eventType == 'ITEM_DESTROYED') {
                            $event_obj->itemId = $event->itemId;
                            $event_obj->participantId = $event->participantId;
                        } else if($event->eventType == 'ITEM_SOLD') {
                            $event_obj->itemId = $event->itemId;
                            $event_obj->participantId = $event->participantId;
                        } else if($event->eventType == 'WARD_PLACED') {
                            $event_obj->creatorId = $event->creatorId;
                            $event_obj->wardType = $event->wardType;
                        } else if($event->eventType == 'WARD_KILL') {
                            $event_obj->killerId = $event->killerId;
                            $event_obj->wardType = $event->wardType;
                        } else if($event->eventType == 'CHAMPION_KILL') {
                            $event_obj->killerId = $event->killerId;
                            $event_obj->victimId = $event->victimId;
                            $event_obj->pos_x = $event->position->x;
                            $event_obj->pos_y = $event->position->y;
                            if(sizeof($event->assistingParticipantIds) > 0) {
                                $t_participants = "";
                                for($j = 0; $j < sizeof($event->assistingParticipantIds); $j++) {
                                    $t_participants .= $event->assistingParticipantIds[$j] . ',';
                                }
                                $assistParticipants = substr($t_participants, 0, strlen($t_participants) - 1);
                            }
                            $event_obj->assistingParticipantIds = $assistParticipants;
                        } else if($event->eventType == 'BUILDING_KILL') {
                            $event_obj->killerId = $event->killerId;
                            $event_obj->teamId = $event->teamId;
                            $event_obj->pos_x = $event->position->x;
                            $event_obj->pos_y = $event->position->y;
                            $event_obj->laneType = $event->laneType;
                            $event_obj->buildingType = $event->buildingType;
                            $event_obj->towerType = $event->towerType;
                            if(sizeof($event->assistingParticipantIds) > 0) {
                                $t_participants = "";
                                for($j = 0; $j < sizeof($event->assistingParticipantIds); $j++) {
                                    $t_participants .= $event->assistingParticipantIds[$j] . ',';
                                }
                                $assistParticipants = substr($t_participants, 0, strlen($t_participants) - 1);
                            }
                            $event_obj->assistingParticipantIds = $assistParticipants;
                        } else if($event->eventType == 'ELITE_MONSTER_KILL') {
                            $event_obj->killerId = $event->killerId;
                            $event_obj->pos_x = $event->position->x;
                            $event_obj->pos_y = $event->position->y;
                            $event_obj->monsterType = $event->monsterType;
                        }
                        $event_arr[] = $event_obj;
                    }
                }

            }
        }
        return $event_arr;
    }

    function MatchParticipantTimeline($arr) {
        $pFrame_arr = array();
        foreach($arr AS $i=>$val) {
            if($i == 'timeline') {
                for($i = 1; $i < sizeof($val->frames); $i++) {
                    $curr_time = $val->frames[$i]->timestamp;
                    foreach($val->frames[$i]->participantFrames AS $pFrame) {
                        $pFrame_obj = new stdClass();
                        $pFrame_obj->participantId = $pFrame->participantId;
                        $pFrame_obj->timestamp = $curr_time;
                        $pFrame_obj->pos_x = $pFrame->position->x;
                        $pFrame_obj->pos_y = $pFrame->position->y;
                        $pFrame_obj->currentGold = $pFrame->currentGold;
                        $pFrame_obj->totalGold = $pFrame->totalGold;
                        $pFrame_obj->level = $pFrame->level;
                        $pFrame_obj->xp = $pFrame->xp;
                        $pFrame_obj->minionsKilled = $pFrame->minionsKilled;
                        $pFrame_obj->jungleMinionsKilled = $pFrame->jungleMinionsKilled;
                        $pFrame_obj->dominionScore = $pFrame->dominionScore;
                        $pFrame_obj->teamScore = $pFrame->teamScore;
                        $pFrame_arr[] = $pFrame_obj;
                    }
                }

            }
        }
        return $pFrame_arr;
    }

    function MatchParticipantDeltas($arr) {
        $delta_arr = new stdClass();
        foreach($arr AS $i=>$val) {
            if($i == 'participants') {
                for($j = 0; $j < sizeof($val); $j++) {

                    $creepsPerMinDeltas = new stdClass();
                    $creepsPerMinDeltas->teamId = $val[$j]->teamId;
                    $creepsPerMinDeltas->participantId = $val[$j]->participantId;
                    $creepsPerMinDeltas->zeroToTen = $val[$j]->timeline->creepsPerMinDeltas->zeroToTen;
                    $creepsPerMinDeltas->tenToTwenty = $val[$j]->timeline->creepsPerMinDeltas->tenToTwenty;
                    $creepsPerMinDeltas->twentyToThirty = $val[$j]->timeline->creepsPerMinDeltas->twentyToThirty;
                    $creepsPerMinDeltas->thirtyToEnd = $val[$j]->timeline->creepsPerMinDeltas->thirtyToEnd;

                    $xpPerMinDeltas = new stdClass();
                    $xpPerMinDeltas->zeroToTen = $val[$j]->timeline->xpPerMinDeltas->zeroToTen;
                    $xpPerMinDeltas->tenToTwenty = $val[$j]->timeline->xpPerMinDeltas->tenToTwenty;
                    $xpPerMinDeltas->twentyToThirty = $val[$j]->timeline->xpPerMinDeltas->twentyToThirty;
                    $xpPerMinDeltas->thirtyToEnd = $val[$j]->timeline->xpPerMinDeltas->thirtyToEnd;

                    $goldPerMinDeltas = new stdClass();
                    $goldPerMinDeltas->zeroToTen = $val[$j]->timeline->goldPerMinDeltas->zeroToTen;
                    $goldPerMinDeltas->tenToTwenty = $val[$j]->timeline->goldPerMinDeltas->tenToTwenty;
                    $goldPerMinDeltas->twentyToThirty = $val[$j]->timeline->goldPerMinDeltas->twentyToThirty;
                    $goldPerMinDeltas->thirtyToEnd = $val[$j]->timeline->goldPerMinDeltas->thirtyToEnd;

                    $csDiffPerMinDeltas = new stdClass();
                    $csDiffPerMinDeltas->zeroToTen = $val[$j]->timeline->csDiffPerMinDeltas->zeroToTen;
                    $csDiffPerMinDeltas->tenToTwenty = $val[$j]->timeline->csDiffPerMinDeltas->tenToTwenty;
                    $csDiffPerMinDeltas->twentyToThirty = $val[$j]->timeline->csDiffPerMinDeltas->twentyToThirty;
                    $csDiffPerMinDeltas->thirtyToEnd = $val[$j]->timeline->csDiffPerMinDeltas->thirtyToEnd;

                    $xpDiffPerMinDeltas = new stdClass();
                    $xpDiffPerMinDeltas->zeroToTen = $val[$j]->timeline->xpDiffPerMinDeltas->zeroToTen;
                    $xpDiffPerMinDeltas->tenToTwenty = $val[$j]->timeline->xpDiffPerMinDeltas->tenToTwenty;
                    $xpDiffPerMinDeltas->twentyToThirty = $val[$j]->timeline->xpDiffPerMinDeltas->twentyToThirty;
                    $xpDiffPerMinDeltas->thirtyToEnd = $val[$j]->timeline->xpDiffPerMinDeltas->thirtyToEnd;

                    $damageTakenPerMinDeltas = new stdClass();
                    $damageTakenPerMinDeltas->zeroToTen = $val[$j]->timeline->damageTakenPerMinDeltas->zeroToTen;
                    $damageTakenPerMinDeltas->tenToTwenty = $val[$j]->timeline->damageTakenPerMinDeltas->tenToTwenty;
                    $damageTakenPerMinDeltas->twentyToThirty = $val[$j]->timeline->damageTakenPerMinDeltas->twentyToThirty;
                    $damageTakenPerMinDeltas->thirtyToEnd = $val[$j]->timeline->damageTakenPerMinDeltas->thirtyToEnd;

                    $damageTakenDiffPerMinDeltas = new stdClass();
                    $damageTakenDiffPerMinDeltas->zeroToTen = $val[$j]->timeline->damageTakenDiffPerMinDeltas->zeroToTen;
                    $damageTakenDiffPerMinDeltas->tenToTwenty = $val[$j]->timeline->damageTakenDiffPerMinDeltas->tenToTwenty;
                    $damageTakenDiffPerMinDeltas->twentyToThirty = $val[$j]->timeline->damageTakenDiffPerMinDeltas->twentyToThirty;
                    $damageTakenDiffPerMinDeltas->thirtyToEnd = $val[$j]->timeline->damageTakenDiffPerMinDeltas->thirtyToEnd;

                    $delta_arr->creepsPerMinDeltas = $creepsPerMinDeltas;
                    $delta_arr->xpPerMinDeltas = $xpPerMinDeltas;
                    $delta_arr->goldPerMinDeltas = $goldPerMinDeltas;
                    $delta_arr->csDiffPerMinDeltas = $csDiffPerMinDeltas;
                    $delta_arr->xpDiffPerMinDeltas = $xpDiffPerMinDeltas;
                    $delta_arr->damageTakenPerMinDeltas = $damageTakenPerMinDeltas;
                    $delta_arr->damageTakenDiffPerMinDeltas = $damageTakenDiffPerMinDeltas;
                }
            }
        }
        return $delta_arr;
    }

    function GetFrameInterval($arr) {
        $frameInterval = -1;
        foreach($arr AS $i=>$val) {
            if($i == 'timeline') {
                $frameInterval = $val->frameInterval;
            }
        }
        return $frameInterval;
    }

    function GetURL() {
        return $this->url_match;
    }
}

class league_data_collection extends LeagueMatchDetails {

    function InsertNewMatch($match_id) {

    }

}
