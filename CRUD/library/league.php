<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/03/14
 * Time: 11:31 AM
 */

class league
{
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

    function BuildAllMatchStats()
    {
        $retVal = -1;
        $query = 'select mh.matchid, first_game, outcome, date, forfeitattwenty
                 from MatchDetails md
                JOIN MatchHeader mh ON md.matchid = mh.matchid
                WHERE (mh.matchid IN (SELECT DISTINCT gameId from api_MatchHistory
where summonerId in (select summonerId from SummonerGroups where group_id = 1))
      OR mh.matchid < 16)
      AND first_game <> -1
                ORDER BY date DESC, mh.matchid DESC';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('match_id' => '', 'outcome' => '', 'date' => ''
                , 'ff' => '', 'fg' => '');
                $detail->match_id = $row["matchid"];
                $detail->outcome = $row["outcome"];
                $detail->date = $row["date"];
                $detail->ff = $row["forfeitattwenty"];
                $detail->fg = $row["first_game"];
                $this->all_matches[] = $detail;
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function BuildMatchStatsMoreSkins()
    {
        $retVal = -1;
        $query = 'select mh.matchid, first_game, outcome, date, forfeitattwenty
                 from MatchDetails md
                JOIN MatchHeader mh ON md.matchid = mh.matchid
                WHERE (mh.matchid IN (SELECT DISTINCT gameId from api_MatchHistory
where summonerId in (select summonerId from SummonerGroups where group_id = 1))
      OR mh.matchid < 16)
      AND first_game <> -1
AND myteamskins > md.otherteamskins
                ORDER BY date DESC, mh.matchid DESC';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('match_id' => '', 'outcome' => '', 'date' => ''
                , 'ff' => '', 'fg' => '');
                $detail->match_id = $row["matchid"];
                $detail->outcome = $row["outcome"];
                $detail->date = $row["date"];
                $detail->ff = $row["forfeitattwenty"];
                $detail->fg = $row["first_game"];
                $this->more_skin_matches[] = $detail;
                if ($row["outcome"] == 1) {
                    $this->matches_won_more_skins = $this->matches_won_more_skins + 1;
                }
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function BuildMatchStatsEqualSkins()
    {
        $retVal = -1;
        $query = 'select mh.matchid, first_game,outcome, date, forfeitattwenty
                 from MatchDetails md
                JOIN MatchHeader mh ON md.matchid = mh.matchid
                WHERE (mh.matchid IN (SELECT DISTINCT gameId from api_MatchHistory
where summonerId in (select summonerId from SummonerGroups where group_id = 1))
      OR mh.matchid < 16)
      AND first_game <> -1
AND myteamskins = md.otherteamskins
                ORDER BY date DESC, mh.matchid DESC';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('match_id' => '', 'outcome' => '', 'date' => ''
                , 'ff' => '', 'fg' => '');
                $detail->match_id = $row["matchid"];
                $detail->outcome = $row["outcome"];
                $detail->date = $row["date"];
                $detail->ff = $row["forfeitattwenty"];
                $detail->fg = $row["first_game"];
                $this->equal_skin_matches[] = $detail;
                if ($row["outcome"] == 1) {
                    $this->matches_won_equal_skins = $this->matches_won_equal_skins + 1;
                }
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function BuildMatchStatsLessSkins()
    {
        $retVal = -1;
        $query = 'select mh.matchid, first_game, outcome, date, forfeitattwenty
                 from MatchDetails md
                JOIN MatchHeader mh ON md.matchid = mh.matchid
                WHERE (mh.matchid IN (SELECT DISTINCT gameId from api_MatchHistory
where summonerId in (select summonerId from SummonerGroups where group_id = 1))
      OR mh.matchid < 16)
      AND first_game <> -1
AND myteamskins < md.otherteamskins
                ORDER BY date DESC, mh.matchid DESC';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('match_id' => '', 'outcome' => '', 'date' => ''
                , 'ff' => '', 'fg' => '');
                $detail->match_id = $row["matchid"];
                $detail->outcome = $row["outcome"];
                $detail->date = $row["date"];
                $detail->ff = $row["forfeitattwenty"];
                $detail->fg = $row["first_game"];
                $this->less_skin_matches[] = $detail;
                if ($row["outcome"] == 1) {
                    $this->matches_won_less_skins = $this->matches_won_less_skins + 1;
                }
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }


    function BuildPlayersMap()
    {
        $retVal = -1;
        $this->player_map .= '<option value="0"> Random </option>';
        $query = 'select *
                     from Players';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $this->player_map .= '<option value="' . $row['playerid'] . '"> ';
                $this->player_map .= $row['league_name'];
                $this->player_map .= ' </option>';
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal .= 0;
        }
        return $retVal;
    }

    function BuildGroupMembers($groupid)
    {
        $retVal = -1;
        $query = 'select summonerId, league_name from SummonerGroups sg
                  JOIN Players p ON sg.summonerId = p.playerid
                  WHERE group_id = ' . $groupid;
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('summonerId' => '', 'summonerName' => '');
                $detail->summonerId = $row["summonerId"];
                $detail->summonerName = $row["league_name"];
                $this->group_members[] = $detail;
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function BuildChampionsForAutoComplete($term)
    {
        $retVal = -1;
        $query = 'select champ_id, champ_name
                     from champions
                     WHERE champ_name LIKE "%' . $term . '%"';
        //echo $query;
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $t_array = array(
                    'data' => $row['champ_id'],
                    'value' => $row['champ_name']
                );
                array_push($this->list_of_champions, $t_array);
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal .= 0;
        }
        return $retVal;
    }

    function BuildMatchDetails($matchid)
    {
        $retVal = -1;
        $query = 'call getMatchDetails(' . $matchid . ')';
        //echo $query . ".....";
        if ($result = $this->mys->query($query)) {
            //var_dump($result->fetch_assoc());
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('matchid' => '', 'date' => '',
                    'outcome' => '', 'mts' => '', 'ots' => '', 'ff' => '', 'first_game' => '',
                    'top_player' => '', 'mid_player' => '', 'jungle_player' => '',
                    'support_player' => '', 'adc_player' => '',
                    'c_our_top' => '', 'c_our_mid' => '', 'c_our_jun' => '',
                    'c_our_sup' => '', 'c_our_adc' => '',
                    'c_ene_top' => '', 'c_ene_mid' => '', 'c_ene_jun' => '',
                    'c_ene_sup' => '', 'c_ene_adc' => '');
                $detail->matchid = $row["matchid"];
                $detail->date = $row["date"];
                $detail->outcome = $row["outcome"];
                $detail->mts = $row["mts"];
                $detail->ots = $row["ots"];
                $detail->ff = $row["ff"];
                $detail->first_game = $row["first_game"];
                //var_dump($row);
                //echo $row["matchid"] . " ....... ";
                /* Player names */
                $detail->top_player = $row["top_player"];
                $detail->mid_player = $row["mid_player"];
                $detail->jungle_player = $row["jungle_player"];
                $detail->support_player = $row["support_player"];
                $detail->adc_player = $row["adc_player"];

                /*Our team comp*/
                $detail->c_our_top = $row["c_our_top"];
                $detail->c_our_mid = $row["c_our_mid"];
                $detail->c_our_jun = $row["c_our_jun"];
                $detail->c_our_sup = $row["c_our_sup"];
                $detail->c_our_adc = $row["c_our_adc"];

                /*their team comp*/
                $detail->c_ene_top = $row["c_ene_top"];
                $detail->c_ene_mid = $row["c_ene_mid"];
                $detail->c_ene_jun = $row["c_ene_jun"];
                $detail->c_ene_sup = $row["c_ene_sup"];
                $detail->c_ene_adc = $row["c_ene_adc"];
                //var_dump($detail);
                $this->match_details[] = $detail;
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function BuildPlayerArray()
    {
        $retVal = -1;
        $query = 'select * from Players';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('player_id' => '', 'first_name' => '',
                    'last_name' => '', 'lol_name' => '');
                $detail->player_id = $row["playerid"];
                $detail->first_name = $row["first_name"];
                $detail->last_name = $row["last_name"];
                $detail->lol_name = $row["league_name"];
                $this->list_of_players[] = $detail;
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    /**
     * @return string
     */
    function BuildGroupMap()
    {
        $retVal = '<select id="select_group">';
        $retVal .= '<option value="-1">  </option>';
        $query = 'select DISTINCT group_id from SummonerGroups';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $retVal .= '<option value="' . $row['group_id'] . '"> ' . $row['group_id'] . ' </option>';
            }
            $result->free();
        } else {
            $retVal .= "";
        }
        $retVal .= "</select>";
        return $retVal;
    }

    function GetMatchDetails()
    {
        return $this->match_details;
    }

    function GetListOfPlayers()
    {
        return $this->list_of_players;
    }

    function GetListOfChampions()
    {
        return $this->list_of_champions;
    }

    function GetPlayerMap()
    {
        return $this->player_map;
    }

    function GetGroupMembers()
    {
        return $this->group_members;
    }

    function GetNumberOfAllMatchesWon()
    {
        $retVal = -1;
        $query = 'select COUNT(*) AS num_win
from MatchDetails md
  JOIN MatchHeader mh ON md.matchid = mh.matchid
where (mh.matchid IN (SELECT DISTINCT gameId from api_MatchHistory
where summonerId in (select summonerId from SummonerGroups where group_id = 1))
       OR mh.matchid < 16)
      AND first_game <> -1
      AND outcome = 1;';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $retVal = $row["num_win"];
            }
            $result->free();
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function GetNumberOfMatchesWonMoreSkin()
    {
        return $this->matches_won_more_skins;
    }

    function GetNumberOfMatchesWonLessSkin()
    {
        return $this->matches_won_less_skins;
    }

    function GetNumberOfMatchesWonEqualSkin()
    {
        return $this->matches_won_equal_skins;
    }

    /*
     * Gets an array of all_matches
     *
     * returns:
     *	@this->all_matches
     */
    function GetAllMatches()
    {
        return $this->all_matches;
    }

    /*
     * Gets an array of all_matches
     *
     * returns:
     *	@this->all_matches
     */
    function GetMatchesMoreSkin()
    {
        return $this->more_skin_matches;
    }

    /*
     * Gets an array of all_matches
     *
     * returns:
     *	@this->all_matches
     */
    function GetMatchesLessSkin()
    {
        return $this->less_skin_matches;
    }

    /*
     * Gets an array of matches where teams
     * had equal number of skins
     *
     * returns:
     *	@this->all_matches
     */
    function GetMatchesEqualSkin()
    {
        return $this->equal_skin_matches;
    }

    /*
     * Returns a true-to-size count of all matches
     *
     * returns:
     *	sizeof($this->all_matches);
     */
    function GetNumberOfAllMatches()
    {
        return sizeof($this->all_matches);
    }

    /*
     * Returns a true-to-size count of all matches
     *
     * returns:
     *	sizeof($this->all_matches);
     */
    function GetNumberOfMatchesMS()
    {
        return sizeof($this->more_skin_matches);
    }

    /*
     * Returns a true-to-size count of matches
     * where we had LESS skins
     *
     * returns:
     *	sizeof($this->all_matches);
     */
    function GetNumberOfMatchesLS()
    {
        return sizeof($this->less_skin_matches);
    }

    /*
     * Returns a true-to-size count of matches
     * where we had EQUAL skins
     *
     * returns:
     *	sizeof($this->all_matches);
     */
    function GetNumberOfMatchesEQ()
    {
        return sizeof($this->equal_skin_matches);
    }

    function GetMaxMatchID()
    {
        $retVal = -1;
        $query = 'select MAX(matchid) AS max_id
                     from MatchHeader';
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

    /** INSERT/UPDATE/DELETE **/


    function InsertNewMatch($gameId, $outcome, $date, $ff, $mt, $ot, $first_game, $top, $mid,
                            $jung, $supp, $adc, $our_top, $our_mid
        , $our_jun, $our_sup, $our_adc, $ene_top
        , $ene_mid, $ene_jun, $ene_sup, $ene_adc)
    {
        $retVal = -1;
        $id = $gameId;
        if ($this->InsertIntoMatchHeader($id, $outcome, $date) == 1) {
            $this->mys->next_result();
            if ($this->InsertIntoMatchDetails($id, $ff, $mt, $ot, $first_game, $top, $mid,
                    $jung, $supp, $adc) == 1
            ) {
                $this->mys->next_result();
                if ($this->InsertIntoMatchTeamDetails($id, $our_top, $our_mid
                        , $our_jun, $our_sup, $our_adc, $ene_top
                        , $ene_mid, $ene_jun, $ene_sup, $ene_adc) == 1
                ) {
                    $retVal = 1;
                } else {
                    $retVal = 5;
                }
            } else {
                $retVal = 3;
            }
        } else {
            $retVal = 2;
        }
        return $retVal;
    }

    function InsertIntoMatchHeader($id, $outcome, $date)
    {
        $retVal = -1;
        $query = "INSERT INTO MatchHeader VALUES(?,?,?)";
        //echo $query . ", $id, $outcome, $date\n";
        if ($id <= 1) {
            $retVal = 4;
            echo "NO ID";
        } else {
            $date .= ' ' . date("H:i:s");
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iis', $id, $outcome, $date);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $retVal = 1;
            } else {
                $retVal = 0;
            }
        }
        return $retVal;
    }

    function InsertIntoMatchDetails($id, $ff, $mt, $ot, $first_game, $top, $mid,
                                    $jung, $supp, $adc)
    {
        $retVal = -1;
        $query = "INSERT INTO MatchDetails VALUES(?,?,?,?,?,?,?,?,?,?)";

        if ($id <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiiiiii', $id, $mt, $ot, $top, $mid,
                $jung, $supp, $adc, $ff, $first_game);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $retVal = 1;
            } else {
                $retVal = 0;
            }
        }
        return $retVal;
    }

    function InsertIntoMatchTeamDetails($id, $o_top, $o_mid,
                                        $o_jung, $o_supp, $o_adc,
                                        $e_top, $e_mid,
                                        $e_jung, $e_supp, $e_adc)
    {
        $retVal = -1;
        /*        echo "$id, $o_top, $o_mid,
                $o_jung, $o_supp, $o_adc,
                $e_top, $e_mid,
                $e_jung, $e_supp, $e_adc";*/
        $query = "INSERT INTO MatchTeamDetails VALUES(?,?,?,?,?,?,?,?,?,?,?)";

        if ($id <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('iiiiiiiiiii', $id, $o_top, $o_mid,
                $o_jung, $o_supp, $o_adc,
                $e_top, $e_mid,
                $e_jung, $e_supp, $e_adc);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $retVal = 1;
            } else {
                $retVal = 0;
            }
        }
        return $retVal;
    }

    /**
     * @param $id
     * @param $sum_name
     * @return int
     *      4 - Invalid ID
     *      2 - ID already exists in table
     *      0 - Statement failed to execute
     *      1 - Successfully inserted new player
     */
    function InsertNewPlayer($id, $sum_name)
    {
        $retVal = -1;
        $query = "INSERT INTO Players VALUES(?,?,?,?)";

        $fn = "";
        $ln = "";

        if ($id <= 1) {
            $retVal = 4;
        } else {
            if ($this->doesPlayerIDExist($id) == false) {
                $this->mys->next_result();
                $stmt = $this->mys->prepare($query);
                $stmt->bind_param('isss', $id, $fn, $ln, $sum_name);
                if ($result = $stmt->execute()) {
                    $stmt->close();
                    $retVal = 1;
                } else {
                    $retVal = 0;
                }
            } else {
                $retVal = $this->updatePlayerName($id, $sum_name);
            }
        }
        return $retVal;
    }

    /**
     * @param $id
     * @param $sum_name
     * @return int
     *      6 - Invalid ID
     *      3 - Statement failed to execute
     *      1 - Successfully updated player name
     */
    function updatePlayerName($id, $sum_name)
    {
        $retVal = -1;
        $query = "UPDATE Players SET league_name = ? WHERE playerid = ?";

        $fn = "";
        $ln = "";
        if ($id <= 1) {
            $retVal = 6;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('si', $sum_name, $id);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $retVal = 1;
            } else {
                $retVal = 3;
            }
        }
        return $retVal;
    }

    function doesPlayerIDExist($id)
    {
        $retVal = false;
        $query = 'select COUNT(playerid) AS player_count
                     from Players where playerid = ' . $id . '';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                if ($row["player_count"] > 0) {
                    $retVal = true;
                }
            }
            $result->free();
        } else {
            $retVal = true;
        }
        return $retVal;
    }

    function getChampID($champ_name)
    {
        $retVal = 0;
        $query = 'select champ_id
                     from champions where champ_name = "' . $champ_name . '"';
        //echo $query . "\n";
        if ($champ_name == "-") {
            $retVal = 0;
        } else {
            if ($result = $this->mys->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    $retVal = $row["champ_id"];
                }
                $result->free();
            } else {
                $retVal = 0;
            }
        }
        return $retVal;
    }

    function getChampName($champID)
    {
        $retVal = 0;
        $query = 'select champ_name
                     from champions where champ_id = ' . $champID . '';
        //echo $query . "\n";
        if ($champID < 1) {
            $retVal = "-";
        } else {
            if ($result = $this->mys->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    $retVal = $row["champ_name"];
                }
                $result->free();
            } else {
                $retVal = 0;
            }
        }
        return $retVal;
    }

    function BuildLanePriority()
    {
        $retVal = -1;
        $query = 'select clp.championid, champname,
                    clp.top_priority, clp.jungle_priority, clp.mid_priority,
                    clp.adc_priority, clp.supp_priority,
                    wclp.tp_count, wclp.jp_count, wclp.mp_count,
                    wclp.ap_count, wclp.sp_count
                     from ChampionLanePriority clp
                    join workingChampionLanePriority wclp ON clp.championid = wclp.championid';
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('champ_id' => '', 'champ_name' => '',
                    'tp' => '', 'jp' => '', 'mp' => '', 'ap' => '', 'sp' => '', 'tp_count' => ''
                , 'jp_count' => '', 'mp_count' => '', 'ap_count' => '', 'sp_count' => '');
                $detail->champ_id = $row["championid"];
                $detail->champ_name = $row["champname"];
                $detail->tp = $row["top_priority"];
                $detail->jp = $row["jungle_priority"];
                $detail->mp = $row["mid_priority"];
                $detail->ap = $row["adc_priority"];
                $detail->sp = $row["supp_priority"];
                $detail->tp_count = $row["tp_count"];
                $detail->jp_count = $row["jp_count"];
                $detail->mp_count = $row["mp_count"];
                $detail->ap_count = $row["ap_count"];
                $detail->sp_count = $row["sp_count"];

                $this->list_of_players[] = $detail;
            }
            $result->free();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function GetLanePriority()
    {
        return $this->list_of_players;
    }

    function GetWorkingChampionLanePriority($cid, $priority)
    {
        $retVal = -1;
        $query = "SELECT ";
        if ($priority == "t") {
            $query .= "top_priority ";
        } else if ($priority == "j") {
            $query .= "jungle_priority ";
        } else if ($priority == "m") {
            $query .= "mid_priority ";
        } else if ($priority == "a") {
            $query .= "adc_priority ";
        } else if ($priority == "s") {
            $query .= "supp_priority ";
        }
        $query .= "AS priority FROM workingChampionLanePriority WHERE championid = $cid;";

        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $retVal = $row["priority"];
            }
            $result->free();
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function GetChampionLanePriorityByID($cid)
    {
        $retVal = -1;
        $query = 'select clp.top_priority, clp.jungle_priority, clp.mid_priority,
                    clp.adc_priority, clp.supp_priority
                    from ChampionLanePriority clp
                    WHERE championid = ' . $cid . '';
        $detail = (object)array('cID' => '', 'top' => '', 'jun' => '', 'mid' => '', 'adc' => '', 'sup' => '');
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail->cID = $cid;
                $detail->top = $row["top_priority"];
                $detail->jun = $row["jungle_priority"];
                $detail->mid = $row["mid_priority"];
                $detail->adc = $row["adc_priority"];
                $detail->sup = $row["supp_priority"];
            }
            $result->free();
        } else {
            $detail->cID = $cid;
            $detail->top = -1;
            $detail->jun = -1;
            $detail->mid = -1;
            $detail->adc = -1;
            $detail->sup = -1;
        }
        return $detail;
    }

    function UpdateWorkingChampionLanePriority($cid, $priority, $count, $value)
    {
        $query = "UPDATE workingChampionLanePriority SET ";
        if ($priority == "t") {
            $query .= "top_priority = top_priority + ?, tp_count = tp_count + 1 ";
        } else if ($priority == "j") {
            $query .= "jungle_priority = jungle_priority + ?, jp_count = jp_count + 1 ";
        } else if ($priority == "m") {
            $query .= "mid_priority = mid_priority + ?, mp_count = mp_count + 1 ";
        } else if ($priority == "a") {
            $query .= "adc_priority =  adc_priority + ?, ap_count = ap_count + 1 ";
        } else if ($priority == "s") {
            $query .= "supp_priority = supp_priority + ?, sp_count = sp_count + 1 ";
        }
        $query .= "WHERE championid = ?;";

        //echo $query . "$value $cid";
        $stmt = $this->mys->prepare($query);
        $stmt->bind_param('di', $value, $cid);
        if ($result = $stmt->execute()) {
            $stmt->close();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function UpdateChampionLanePriority($cid, $priority, $value)
    {
        $query = "UPDATE ChampionLanePriority SET ";
        if ($priority == "t") {
            $query .= "top_priority = ? ";
        } else if ($priority == "j") {
            $query .= "jungle_priority = ? ";
        } else if ($priority == "m") {
            $query .= "mid_priority = ? ";
        } else if ($priority == "a") {
            $query .= "adc_priority = ? ";
        } else if ($priority == "s") {
            $query .= "supp_priority = ? ";
        }
        $query .= "WHERE championid = ?;";

        //echo $query . "$value, $cid";
        $stmt = $this->mys->prepare($query);
        $stmt->bind_param('di', $value, $cid);
        if ($result = $stmt->execute()) {
            $stmt->close();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function InsertMatchHistory($matchid, $game_type, $game_mode, $date,
                                $champ_played, $teamId, $outcome, $summonerId)
    {

        $retVal = -1;
        $query = "INSERT INTO api_MatchHistory VALUES(?,?,?,?,?,?,?,?)";

        if ($matchid <= 1) {
            $retVal = 4;
        } else {
            $stmt = $this->mys->prepare($query);
            $stmt->bind_param('isssiiii', $matchid, $game_type, $game_mode, $date,
                $champ_played, $teamId, $outcome, $summonerId);
            if ($result = $stmt->execute()) {
                $stmt->close();
                $retVal = 1;
            } else {
                $retVal = 0;
            }
        }
        return $retVal;
    }

    function doesMatchIDExist($matchid, $sid)
    {
        $retVal = false;
        $query = 'select COUNT(gameId) AS gameId
                     from api_MatchHistory where gameId = ' . $matchid
            . ' AND summonerId = ' . $sid;
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                //echo $row["gameId"];
                if ($row["gameId"] > 0) {
                    $retVal = true;
                }
            }
            $result->free();
        } else {
            $retVal = true;
        }
        return $retVal;
    }

    /**
     * Checks MatchHeader, MatchDetails, and MatchTeamDetails
     * for specific MatchID
     * @param $matchid
     * @return bool
     */
    function doesMatchExist($matchid)
    {
        $retVal = false;
        $query = 'select COUNT(*) AS games from MatchHeader mh
                    JOIN MatchDetails md ON mh.matchid = md.matchid
                    JOIN MatchTeamDetails mtd ON mh.matchid = mtd.match_id
                    where md.matchid = ' . $matchid;
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                //echo $row["gameId"];
                if ($row["games"] > 0) {
                    $retVal = true;
                }
            }
            $result->free();
        } else {
            $retVal = true;
        }
        return $retVal;
    }

    function getMatchesNotReturnedFromAPI($lom, $sid)
    {
        $retVal = -1;
        $query = 'select * from api_MatchHistory
      where gameId NOT IN (' . $lom . ') AND summonerId = ' . $sid .' ORDER BY createDate DESC';
        $x = array();
        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('gameId' => '', 'gameMode' => '', 'gameType' => '', 'createDate' => '',
                    'champ_played' => '', 'teamId' => '', 'outcome' => '');
                $detail->gameId = $row['gameId'];
                $detail->gameMode = $row["gameMode"];
                $detail->gameType = $row["gameType"];
                $detail->createDate = $row["createDate"];
                $detail->champ_played = $row["champ_played"];
                $detail->teamId = $row["teamId"];
                $detail->outcome = $row["outcome"];
                $x[] = $detail;
            }
            $result->free();
        }
        return $x;
    }

    function GetWinPercentageByLane($sid, $lid)
    {
        $retVal = -1;
        $query = 'CALL getLaneWinPercentage(' . $sid . ',' . $lid . ');';
        $total = 0;
        $match_won = 0;
        //echo $query;
        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $match_won = $row["games_won"];
                $total = $match_won + $row["games_lost"];
            }
            $result->free();
        }
        if ($total > 0) {
            $retVal = number_format(100 * ($match_won / $total), 2, '.', '');
        } else {
            $retVal = 'N/A';
        }
        //echo ": " . $retVal . ", ";
        return $retVal;
    }

    function GetTopChampWinPercentage($sid)
    {
        $retVal = -1;
        $query = "CALL getTop10ChampionWinPercentage($sid)";
        $x = array();
        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('champ_id' => '', 'champ_name' => '',
                    'games_won' => '', 'total_games' => '', 'win_perc' => '');
                $detail->champ_id = $row['champ_id'];
                $detail->champ_name = $row["champ_name"];
                $detail->games_won = $row["games_won"];
                $detail->total_games = $row["total_games"];
                $detail->win_perc = $row["win_perc"];
                $x[] = $detail;
            }
            $result->free();
        }

        return $x;
    }

    function GetSummonerGroupsWinPerc($sql)
    {
        $x = array();
        $query = "CALL getSummonerGroupsWinPerc('" . $sql . "')";

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = (object)array('matchid' => '', 'outcome' => '',
                    'top' => '', 'mid' => '', 'jungle' => ''
                , 'support' => '', 'adc' => '');
                $detail->matchid = $row['matchid'];
                $detail->outcome = $row["outcome"];
                $detail->top = $row["top"];
                $detail->mid = $row["mid"];
                $detail->jungle = $row["jungle"];
                $detail->support = $row["support"];
                $detail->adc = $row["adc"];
                $x[] = $detail;
            }
            $result->free();
        }
        return $x;
    }

    function GetMemberOfGroup($gid)
    {
        $x = array();
        $query = 'select summonerId from SummonerGroups
                  WHERE group_id = ' . $gid;
        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $x[] = $row['summonerId'];
            }
            $result->free();
        }
        return $x;
    }

    function GetSummonerName($id)
    {
        $x = "";
        $query = 'select league_name from Players
                  WHERE playerid = ' . $id;
        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $x = $row['league_name'];
            }
            $result->free();
        }
        return $x;
    }

    function UpdateMatchDetails($gameId, $ff, $my_team, $other_team, $first_game,
                                $top, $mid, $jung, $supp, $adc)
    {

        $query = "UPDATE MatchDetails
                  SET myteamskins = ?
                  , otherteamskins = ?
                  , forfeitattwenty = ?
                  , first_game = ?
                  , top = ?
                  , mid = ?
                  , jungle = ?
                  , support = ?
                  , adc = ?
                  WHERE matchid = ?";

        //echo $query . "$value, $cid";
        $stmt = $this->mys->prepare($query);
        $stmt->bind_param('iiiiiiiiii', $my_team, $other_team, $ff, $first_game,
            $top, $mid, $jung, $supp, $adc, $gameId);
        if ($result = $stmt->execute()) {
            $stmt->close();
            $retVal = 1;
        } else {
            $retVal = 0;
        }
        return $retVal;
    }

    function FetchDataForSummonerNetwork($sid)
    {
        $parent = array();
        $x = array();
        $children = array();
        $query = "CALL getNetworkOfSummoners($sid)";
        $s_name = $this->GetSummonerName($sid);
        $first_detail = (object)array('summonerId' => $sid,
            'summonerName' => $s_name,
            'children' => '',
            'name' => $s_name,
            'parent' => NULL);

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $detail = new stdClass();
                $detail->summonerId = $row['summonerId'];
                $detail->summonerName = $row["summonerName"];
                $detail->name = $row["summonerName"];
                $detail->parent = $s_name;
                $children[] = $detail;
            }
            $result->free();
        }
        $first_detail->children = $children;
        $parent[] = $first_detail;
        return $parent;
    }

    function FetchDataForSummonerNetwork_FD($sid)
    {
        $main_string = "";
        $nodes = '"nodes":[';
        $links = '"links":[';
        $index = 1;

        $target = 0;
        $source = 0;
        $type = 1;
        $value = 1;
        $degree = 0;

        $distance = 10;

        $html = '<div class="recent_games_size">';
        $html .= '<table id="win_perc_by_lane_table" class="table table-striped table-hover">';
        $html .= '<thead class="header_bg">
            <th></th>
            <th>Source</th>
            <th>Target</th>
            <th>Type</th>
            <th>Name</th>
            <th>Function</th>
            <th> </th>
            <th> </th>
            <th>RCalls</th>
            </tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $query = "CALL getNetworkOfSummoners($sid)";
        $s_name = $this->GetSummonerName($sid);
        $html .= '<tr>';
        $html .= '<td >'.$this->global_counter.'</td>';
        $html .= '<td > - </td>';
        $html .= '<td > - </td>';
        $html .= '<td >'.$index.'</td>';
        $html .= '<td >'.$s_name.'</td>';
        $html .= '<td >FetchDataForSummonerNetwork_FD</td>';
        $html .= '<td > pre-while</td>';
        $html .= '<td > </td>';
        $html .= '<td >'.$this->recursive_calls.'</td>';
        $html .= '</tr>';
        $this->temp_table = $html;
        $this->global_counter = $this->global_counter + 1;

        $this->player_nodes .= '{"name":"' . $s_name . '"
                                , "type":"'.$type.'"
                                , "full_name":"' . $s_name . '"
                                , "slug":""
                                , "entity":"company"
                                , "img_hrefD":""
                                , "img_hrefL":""
                                },';

        $this->mys->next_result();
        if ($result = $this->mys->query($query)) {
            $type = $type + 1;
            while ($row = $result->fetch_assoc()) {
                $target = $index;
                $degree = 1;
                if ($index % 10 == 0) {
                    $distance = $distance + 10;
                }

                $this->player_nodes .= '{"name":"'.$row['summonerName'].'"
                , "type":"'.$type.'", "full_name":"'.$row['summonerName'].'", "slug":""
                , "entity":"company", "img_hrefD":"", "img_hrefL":"", "summonerId":"'.$row['summonerId'].'"},';

                $this->player_links .= '{"source":'.$source.', "target":' . $target . '
                , "distance":' . $distance . ', "value":'.$value.'},';

                $html = '<tr>';
                $html .= '<td >'.$this->global_counter.'</td>';
                $html .= '<td >'.$source.'</td>';
                $html .= '<td >'.$target.'</td>';
                $html .= '<td >'.$index.'</td>';
                $html .= '<td >'.$row['summonerName'].'</td>';
                $html .= '<td > FetchDataForSummonerNetwork_FD </td>';
                $html .= '<td > in while(), pre-recursive_NoS call</td>';
                $html .= '<td > </td>';
                $html .= '<td >'.$this->recursive_calls.'</td>';
                $html .= '</tr>';
                $this->temp_table .= $html;
                $this->global_counter = $this->global_counter + 1;

                $this->recursive_NoS($row['summonerId'], $degree,
                                        $type, $value, $source + 1,
                                        $distance, $index);
                $this->recursive_calls = $this->recursive_calls + 1;
                //$source = $source + 1;
                $index = $index + 1;
            }
            $result->free();
        }

        $nodes .= substr($this->player_nodes, 0, strlen($this->player_nodes) - 1);
        $links .= substr($this->player_links, 0, strlen($this->player_links) - 1);

        $main_string = "{" . $nodes . "]," . $links . "]}";
        $html = '</tbody></table>';
        $html .= '</div>';
        $this->temp_table .= $html;
        return $main_string;
    }

    function recursive_NoS($id, $degree, $type, $value, $source, $distance, &$index) {

        $t_id = $id;
        $t_degree = $degree;
        $t_type = $type;
        $t_value = $value;

        $t_distance = ($distance * $degree);

        if($index == 1) {
            $t_source = $source;
        } else {
            $t_source = $this->recursive_calls + 1;
        }
        $html = "";
        $target = 0;
        $t_source = $index;
        $my_counter = 0;

        $query = "CALL getNetworkOfSummoners($id)";

        if($degree < 2) {
            $this->mys->next_result();
            if ($result = $this->mys->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    $target = $index + 1;
                    $html .= '<tr>';
                    $html .= '<td >'.$this->global_counter.'</td>';
                    $html .= '<td >'.$t_source.'</td>';
                    $html .= '<td >'.$target.'</td>';
                    $html .= '<td >'.$index.'</td>';
                    $html .= '<td >TEST'.$target.'</td>';
                    $html .= '<td >recursive_NoS</td>';
                    $html .= '<td > in while() </td>';
                    $html .= '<td > </td>';
                    $html .= '<td >'.$this->recursive_calls.'</td>';
                    $html .= '</tr>';


                    $this->player_nodes .= '{"name":"'.$row['summonerName'].'"
                , "type":3, "full_name":"'.$row['summonerName'].'", "slug":""
                , "entity":"company", "img_hrefD":"", "img_hrefL":"", "summonerId":"'.$row['summonerId'].'"},';

                    $this->player_links .= '{"source":'.$t_source.', "target":' . $target . '
                , "distance":' . $t_distance . ', "value":10},';

                    $my_counter = $my_counter + 1;
                    $index = $index + 1;
                    $this->global_counter = $this->global_counter + 1;
                }
            }
            $this->temp_table .= $html;
            $this->recursive_NoS($t_id+1, $t_degree + 1, $t_type + 1, $t_value + 1, $t_source+1, $t_distance, $index);
        }

    }
}

