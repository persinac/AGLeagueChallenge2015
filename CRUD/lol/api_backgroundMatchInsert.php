<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 *
 * User clicks on a summoner from player list or searches for a
 * summoner, then after load, and after the insert in api_MatchHistory,
 * this process will be run.
 *
 * There is a session variable $_SESSION['game_details'] that stores an array
 * of details that get inserted into api_MatchHistory. Using this data,
 * the View Team Details page is generated.
 *
 * No return html
 *
 * Created by PhpStorm.
 * User: APersinger
 * Date: 01/08/15
 * Time: 1:52 PM
 */
session_start();

include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/array_utilities.php');
include('../../CRUD/library/Validate.php');
include('../../CRUD/library/league.php');
include('../../CRUD/library/league_html_builder.php');
include('../../CRUD/library/Champion.php');
include('../../CRUD/library/Team.php');
include('../../CRUD/lol/buildAddNewMatch.php');

$champion = new LeagueChampions($_SESSION['region'], $lol_host, $lol_un, $lol_pw, $lol_db);
$league_priority = new league();
//$champion->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$league_priority->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

$create_date = '';
$outcome = '';
$temp_my_team = 1;

$player_temp = '';
$mychamp_temp = '';
$t_gameMode = '';
$list_of_game_ids = '';
//for all new matches
if(isset($_SESSION['game_details'])) {
    foreach ($_SESSION['game_details'] AS $i=>$val) {
        if($league_priority->doesMatchExist($val->gameid) != true) {
            doEverything($val, $list_of_game_ids, $league_priority,
                $lol_host, $lol_un, $lol_pw, $lol_db);
        } /*else {
            echo " MatchID: " . $val->gameid . " already exists in table, no need to call API ";
        }*/
    }
}

//check all old matches (shouldn't hit DB much after matches start getting inserted)
$t = $league_priority->getMatchesNotReturnedFromAPI(substr($list_of_game_ids,0, strlen($list_of_game_ids)-1),  $_SESSION['summonerId']);
foreach($t AS $i=>$val) {
    //echo $val->gameId . ", ";
}

$champion->CloseConnection();
$league_priority->CloseConnection();

function doEverything($val, &$logi, $prio_obj, $host, $un, $pw, $db) {
    $t_gameMode = $val->game_mode;
    $t_gameid = $val->gameid;
    $logi .= $t_gameid. ",";
    $outcome = $val->win;
    $create_date = $val->date;
    $temp_my_team = $val->myteam;
    $player_temp = json_decode($val->fellowPlayers);
    $me = array('summonerId'=>$_SESSION['summonerId'], 'teamId'=>$val->myteam,'championId'=>$val->whoIPlayed);
    $all_players = addPlayerToArray($player_temp, $_SESSION['summonerId'], $val->myteam,$val->whoIPlayed );
    $mychamp_temp = $val->whoIPlayed;

    buildMatchDetails($t_gameid, $prio_obj, $host, $un, $pw, $db);
    AddSummonerID($all_players, $_SESSION['test_champ']);
    $fourth = $_SESSION['test_champ'];
    $_SESSION['summonerIDs'] = array();
    for($i=0;$i<sizeof($fourth);$i++) {
        $_SESSION['summonerIDs'][] = $fourth[$i]->summonerID;
    }
    $blue_team2 = getEachTeam($fourth, "100");
    validation($blue_team2, $prio_obj);
    $purple_team2 = getEachTeam($fourth, "200");
    validation($purple_team2, $prio_obj);

    csValidation($blue_team2);
    csValidation($purple_team2);
    /*for($i = 0; $i < sizeof($blue_team2); $i++) {
        echo $blue_team2[$i]->summonerID . ", " . $blue_team2[$i]->name . ".....";
    }*/
    if($t_gameMode == 'ARAM' || $t_gameMode == 'KINGPORO' || sizeof($blue_team2) < 5) {
        //do nothing. don't add/keep anything except for Classic matches basically
    } else {
        //get blue team position/lane indexes
        $b_top = getIndexOfLaneAndRole($blue_team2,"TOP","SOLO");
        $b_jungle = getIndexOfLaneAndRole($blue_team2,"JUNGLE","NONE");
        $b_mid = getIndexOfLaneAndRole($blue_team2,"MIDDLE","SOLO");
        $b_adc = getIndexOfLaneAndRole($blue_team2,"BOTTOM","DUO_CARRY");
        $b_support = getIndexOfLaneAndRole($blue_team2,"BOTTOM","DUO_SUPPORT");

        //get purple team position/lane indexes
        $p_top = getIndexOfLaneAndRole($purple_team2,"TOP","SOLO");
        $p_jungle = getIndexOfLaneAndRole($purple_team2,"JUNGLE","NONE");
        $p_mid = getIndexOfLaneAndRole($purple_team2,"MIDDLE","SOLO");
        $p_adc = getIndexOfLaneAndRole($purple_team2,"BOTTOM","DUO_CARRY");
        $p_support = getIndexOfLaneAndRole($purple_team2,"BOTTOM","DUO_SUPPORT");

        $ff = -1;
        $mt = 1;
        $ot = -1;
        $first_game = -1;

        //here are all the variables I need except for ff, first game,
        //mySkins, and otherSkins, insert into Match Header, Match Details,
        // and Match Team Details
        if($temp_my_team == 100) {
            $prio_obj->InsertNewMatch($t_gameid, $outcome, $create_date, $ff, $mt, $ot, $first_game,
                $blue_team2[$b_top]->summonerID, $blue_team2[$b_mid]->summonerID,
                $blue_team2[$b_jungle]->summonerID, $blue_team2[$b_support]->summonerID,
                $blue_team2[$b_adc]->summonerID, $blue_team2[$b_top]->champID,
                $blue_team2[$b_mid]->champID, $blue_team2[$b_jungle]->champID,
                $blue_team2[$b_support]->champID,$blue_team2[$b_adc]->champID,
                $purple_team2[$b_top]->champID,
                $purple_team2[$b_mid]->champID, $purple_team2[$b_jungle]->champID,
                $purple_team2[$b_support]->champID,$purple_team2[$b_adc]->champID);
        } else {
            $prio_obj->InsertNewMatch($t_gameid, $outcome, $create_date, $ff, $mt, $ot, $first_game,
                $blue_team2[$b_top]->summonerID, $blue_team2[$b_mid]->summonerID,
                $blue_team2[$b_jungle]->summonerID, $blue_team2[$b_support]->summonerID,
                $blue_team2[$b_adc]->summonerID, $purple_team2[$b_top]->champID,
                $purple_team2[$b_mid]->champID, $purple_team2[$b_jungle]->champID,
                $purple_team2[$b_support]->champID,$purple_team2[$b_adc]->champID,
                $blue_team2[$b_top]->champID,
                $blue_team2[$b_mid]->champID, $blue_team2[$b_jungle]->champID,
                $blue_team2[$b_support]->champID,$blue_team2[$b_adc]->champID);
        }
    }
}

function buildMatchDetails($gid, $league_obj, $host, $un, $pw, $db) {
    $string = "";
    $match = new LeagueMatchDetails($gid, $_SESSION['region'], $host, $un, $pw, $db);
    $string = json_decode($match->GetMatchDetails(1));
    $_SESSION['test_champ'] = $match->ParticipantDetails($string);
    $_SESSION['team_details'] = $match->TeamDetails($string);
    $match->GetParticipantMatchStats($_SESSION['test_champ'],$string, $league_obj);
}

function getEachTeam($arr, $team) {
    $ret_arr = array();
    for($i = 0; $i < sizeof($arr); $i++) {
        if($arr[$i]->team == $team) {
            $ret_arr[] = $arr[$i];
        }
    }
    return $ret_arr;
}

/**
 * This function creates an instance of Validate and runs the team
 * through a series of team checks to ensure team position accuracy.
 *
 * The if statement should now (as of 12/30/2014) always be true
 * because we are using Champion objects.
 *
 * @param $team - passed as reference so no return is needed
 * @param $league_obj - passed into validation so that we don't have to make several connections
 *      to my database. This way, we open the connection in this file, and close it in this file.
 * @param int $count - while it's an optional parameter with the initial call, as this function validates
 *      and checks the team several times, count with increment and is used to exit the recursion.
 */
function validation(&$team, $league_obj, $count = 0)
{
    $v = new Validate();
    if (is_a($team[0], "Champion")) {
        $obj = $v->new_ValidateLanes($team, $league_obj);
        if ($obj->allLanes == false) {
            $invLanes = $v->new_FindInvalidLanes($obj);
            $v->new_CorrectInvalidLanes($team, $invLanes, $league_obj);
            $obj2 = $v->new_ValidateLanes($team, $league_obj);
            $count++;
            if ($obj2->allLanes == false && $count < 10) {
                validation($team, $league_obj, $count);
            }
        }
    }
}

/**
 * Used in junction with csValidation. May use elsewhere later.
 *
 * Take in the team array, and check each member's role and lane of the team.
 * If the role and lane of the currently checked member equals that of the
 * role and lane passed to the function, then the function returns the index
 * of the champion in the array and stops the for loop.
 *
 * @param $arr - team array to be checked
 * @param $lane - lane that we're looking for
 * @param $role - role that we're looking for
 * @return int - index of the champion in the array. Returns -1 if nothing was found
 */
function getIndexOfLaneAndRole($arr, $lane, $role) {
    $index = -1;
    for($i = 0; $i < sizeof($arr); $i++) {
        if ($arr[$i]->lane == $lane && $arr[$i]->role == $role) {
            $index = $i;
            break;
        } else if ($arr[$i]->lane == $lane && $arr[$i]->role == 'DUO') {
            $index = $i;
            break;
        } else if($arr[$i]->lane == $lane && $arr[$i]->role == 'NONE') {
            $index = $i;
            break;
        }
    }
    return $index;
}

/**
 * Used as a last validation - as of 12/30/2014 - for bottom lane. To ensure that
 * support and ADC are correct.
 *
 * If the ADC CS is less than the Support CS, then it is assumed that the two champions
 * need to be switched. Thus, their roles are switched.
 *
 * @param $team - the team to be checked, and is a reference, so that I don't have to return anything
 */
function csValidation(&$team) {
    $p_adc = getIndexOfLaneAndRole($team,"BOTTOM","DUO_CARRY");
    $p_support = getIndexOfLaneAndRole($team,"BOTTOM","DUO_SUPPORT");
    if($team[$p_adc]->cs < $team[$p_support]->cs) {
        $team[$p_adc]->role = "DUO_SUPPORT";
        $team[$p_support]->role = "DUO_CARRY";
    }
}

function getTeamTotalKills($team) {
    $total = 0;

    for($i = 0; $i < sizeof($team); $i++){
        $total += $team[$i]->kills;
    }

    return $total;
}

function printArray($arr, $my_id, $mychampID) {
    $string = '';
    $max_count = 0;
    foreach($arr AS $i=>$val) {
        $string .= '<p>'.$i.', '.$val->summonerId.', '.$val->championId.'</p>';
        $max_count = $i;
    }
    $string .= '<p>'.($max_count+1).', ' . $my_id . ', '. $mychampID.'</p>';
    return $string;
}
