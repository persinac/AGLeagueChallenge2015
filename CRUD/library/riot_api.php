<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/12/14
 * Time: 9:16 AM
 */

//include('../../CRUD/library/Champion.php');

class riot_api {
    var $key = '826a7fbd-5a9f-492d-bab4-1aef227a7d7d';
    var $region = '';
    var $url_prefix = 'https://na.api.pvp.net/api/lol/';
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
        $this->insertIntoAPILog($url_to_exec, substr($result, 0, 1000), $whereDidIComeFrom);
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
        return $this->url_prefix;
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

    function __construct($mid, $region, $host, $user, $pass, $database) {
        $this->matchid = $mid;
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
        $url_postfix = $this->matchid . '?api_key=' . $this->GetKey();
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
                        $champion = new Champion();
                        $champion->SetParticipantID($val[$j]->participantId);
                        $champion->SetChampionID($val[$j]->championId);
                        $champion->SetTeamID($val[$j]->teamId);
                        $champion->SetRole($val[$j]->timeline->role);
                        $champion->SetLane($val[$j]->timeline->lane);
                        $champion->SetChampLevel($val[$j]->champLevel);
                        $champion->SetDeaths($val[$j]->deaths);
                        $champion->SetAssists($val[$j]->assists);
                        $champion->SetKills($val[$j]->kills);
                        $champion->SetGoldEarned($val[$j]->goldEarned);
                        $champion->SetGoldSpent($val[$j]->goldSpent);
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
                                $val[$j]->teamId, $val[$j]->towerKills, $val[$j]->vilemawKills);
                            $team_arr[] = $team1;
                        } else  if($val[$j]->teamId == 200) {
                            $team2 = new Team($val[$j]->baronKills, $val[$j]->dragonKills,
                                $val[$j]->firstBaron, $val[$j]->firstBlood, $val[$j]->firstDragon,
                                $val[$j]->firstInhibitor, $val[$j]->firstTower, $val[$j]->inhibitorKills,
                                $val[$j]->teamId, $val[$j]->towerKills, $val[$j]->vilemawKills);
                            $team_arr[] = $team2;
                        }
                    }
                }
            }
        }
        return $team_arr;
    }
}

class league_data_collection extends LeagueMatchDetails {

    function InsertNewMatch($match_id) {

    }

}