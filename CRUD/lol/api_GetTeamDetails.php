<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/16/14
 * Time: 11:55 AM
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

$game_id = $_POST['gameid'];
$html = '<div name="api_team_match_det_row" class="row">';
$html .= '<div name="api_team_match_det_div" class="col-lg-12">';
$toGetFellowPlayers = new LeagueMatchDetails($game_id, $_SESSION['region'], $lol_host, $lol_un, $lol_pw, $lol_db);
$create_date = '';
$outcome = '';
$temp_my_team = 1;

$player_temp = '';
$mychamp_temp = '';
$t_gameMode = '';

if(isset($_SESSION['game_details'])) {
    $html .= "<h3>Team Details</h3>";
    foreach ($_SESSION['game_details'] AS $i=>$val) {
        //echo "My gameID: " . $game_id . " = " . $val->gameid . "? ";
        if($val->gameid == $game_id) {

            $_SESSION['gameid'] = $game_id;
            $t_gameMode = $val->game_mode;
            $html .= "<p>".$val->gameid."</p>";
            $html .= "<p>".$val->game_mode."</p>";
            if($val->win == 1) {
                $html .= "<p> WIN </p>";
            } else {
                $html .= "<p> LOSS </p>";
            }
            $outcome = $val->win;
            $create_date = $val->date;
            $temp_my_team = $val->myteam;
            $html .= "<p>".$val->date."</p>";
            if($val->cameFromAPI == false) {
                //var_dump($toGetFellowPlayers->GetMatchDetails());
            }
            $player_temp = json_decode($val->fellowPlayers);
            $me = array('summonerId'=>$_SESSION['summonerId'], 'teamId'=>$val->myteam,'championId'=>$val->whoIPlayed);
            $all_players = addPlayerToArray($player_temp, $_SESSION['summonerId'], $val->myteam,$val->whoIPlayed );
            $mychamp_temp = $val->whoIPlayed;
        }
    }
}
else {
    $html .= "<h3>NO GAME DATA</h3>";
}
$html .= '</div></div>';
//$champion->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$league_priority->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

buildMatchDetails($_SESSION['gameid'], $league_priority, $lol_host, $lol_un, $lol_pw, $lol_db);

AddSummonerID($all_players, $_SESSION['test_champ']);
$fourth = $_SESSION['test_champ'];
$txt = "";
for($i=0;$i<sizeof($fourth);$i++) {
    $txt .=  $fourth[$i]->summonerID. "\n";
}
$html .= "TXT: " . $txt;
file_put_contents("/var/www/listOfSummoners.txt", $txt, FILE_APPEND);

$blue_team2 = getEachTeam($fourth, "100");

//var_dump($purple_team2);
validation($blue_team2, $league_priority);
$purple_team2 = getEachTeam($fourth, "200");
validation($purple_team2, $league_priority);

csValidation($blue_team2);
csValidation($purple_team2);

$html .= '<div name="api_team_table_row" class="row">';
$html .= '<div name="api_team_table_div" class="col-lg-12">';
$html .= '<table id="list_of_player_pos" class="table table-striped table-hover">';
$html .= '<thead><tr>';

if($t_gameMode == 'ARAM' || $t_gameMode == 'KINGPORO' || sizeof($blue_team2) < 5) {
    $html .= '<th>  </th><th> </th><th> </th>';
    $html .= '<th> </th><th> </th><th> </th>';
    $html .= '</tr></thead><tbody>';
    $html .= '<tr><td>Blue Team</td>';
    for($j = 0; $j < sizeof($blue_team2); $j++) {
        $html .= '<td class="text-center">'.$blue_team2[$j]->name.'</td>';
    }
    $html .= '</tr>';
    $html .= '<tr><td>Purple Team</td>';
    for($j = 0; $j < sizeof($purple_team2); $j++) {
        $html .= '<td class="text-center">'.$purple_team2[$j]->name.'</td>';
    }
}else {
    $html .= '<th>  </th><th class="text-center"> Top </th><th class="text-center"> Jungle </th>';
    $html .= '<th class="text-center"> Mid </th><th class="text-center"> ADC </th><th class="text-center"> Support </th>';
    $html .= '</tr></thead><tbody>';
    $html .= '<tr><td>Blue Team</td>';

    $b_top = getIndexOfLaneAndRole($blue_team2,"TOP","SOLO");
    $b_jungle = getIndexOfLaneAndRole($blue_team2,"JUNGLE","NONE");
    $b_mid = getIndexOfLaneAndRole($blue_team2,"MIDDLE","SOLO");
    $b_adc = getIndexOfLaneAndRole($blue_team2,"BOTTOM","DUO_CARRY");
    $b_support = getIndexOfLaneAndRole($blue_team2,"BOTTOM","DUO_SUPPORT");

    $html .= '<td class="text-center">'.$blue_team2[$b_top]->name.'</td>';
    $html .= '<td class="text-center">'.$blue_team2[$b_jungle]->name.'</td>';
    $html .= '<td class="text-center">'.$blue_team2[$b_mid]->name.'</td>';
    $html .= '<td class="text-center">'.$blue_team2[$b_adc]->name.'</td>';
    $html .= '<td class="text-center">'.$blue_team2[$b_support]->name.'</td>';

    $html .= '</tr>';
    $html .= '<tr><td>Purple Team</td>';

    $p_top = getIndexOfLaneAndRole($purple_team2,"TOP","SOLO");
    $p_jungle = getIndexOfLaneAndRole($purple_team2,"JUNGLE","NONE");
    $p_mid = getIndexOfLaneAndRole($purple_team2,"MIDDLE","SOLO");
    $p_adc = getIndexOfLaneAndRole($purple_team2,"BOTTOM","DUO_CARRY");
    $p_support = getIndexOfLaneAndRole($purple_team2,"BOTTOM","DUO_SUPPORT");

    $html .= '<td class="text-center">'.$purple_team2[$p_top]->name.'</td>';
    $html .= '<td class="text-center">'.$purple_team2[$p_jungle]->name.'</td>';
    $html .= '<td class="text-center">'.$purple_team2[$p_mid]->name.'</td>';
    $html .= '<td class="text-center">'.$purple_team2[$p_adc]->name.'</td>';
    $html .= '<td class="text-center">'.$purple_team2[$p_support]->name.'</td>';
}

$html .= '</tr>';
$html .= '</tbody></table>';
if(isset($_SESSION['filler'])) {
   $html .= "<b>*</b><i>match-up(s) may not be accurate</i><b>*</b></br>";
   unset($_SESSION['filler']);
}
$html .= '</div><!-- END DIV --></div><!-- END ROW -->';
if($t_gameMode == 'CLASSIC' || $t_gameMode == 'MATCHED_GAME') {
    $top = array($blue_team2[$b_top], $purple_team2[$p_top]);
    $mid = array($blue_team2[$b_mid], $purple_team2[$p_mid]);
    $sup = array($blue_team2[$b_support], $purple_team2[$p_support]);
    $adc = array($blue_team2[$b_adc], $purple_team2[$p_adc]);
    $jungler = array($blue_team2[$b_jungle], $purple_team2[$p_jungle]);

    $html .= '<div id="accordion_lane_details_row"><h2>Team Details</h2>';
        $html .= '<div name="api_team_details_row" class="row">';
            $html .= '<div name="api_team_details_div" class="col-lg-12">';
                $html .= buildTeamDetailTable($_SESSION['team_details']);
                $html .= '<div id="api_team_details_graphs">';
                    $html .= "<h4>Total team Kills</h4>";
                    $html .= '<div id="total_team_kills" class="chart"></div><!-- END CHART SVG -->';
                    $html .= '</div><!-- END team_details_graphs -->';
                $html .= '</div><!-- END team_details_DIV -->';
        $html .= '</div>';
        $html .= '<h2>Top and Mid</h2>';
        $html .= '<div name="api_match_details_row" class="row">';
            $html .= '<div name="api_match_details_div" class="col-lg-6">';
                $html .= buildMatchLaneDetailTable("Top", $top);
            $html .= '</div><!-- END DIV -->';
            $html .= '<div name="api_match_details_div" class="col-lg-6">';
                $html .= buildMatchLaneDetailTable("Mid", $mid);
            $html .= '</div><!-- END DIV -->';
        $html .= '</div><!-- END ROW -->';
        $html .= '<h2>Bottom Lane</h2>';
        $html .= '<div name="api_match_details_row" class="row">';
            $html .= '<div name="api_match_details_div" class="col-lg-6">';
                $html .= buildMatchLaneDetailTable("ADC", $adc);
            $html .= '</div><!-- END DIV -->';
            $html .= '<div name="api_match_details_div" class="col-lg-6">';
                $html .= buildMatchLaneDetailTable("Support", $sup);
            $html .= '</div><!-- END DIV -->';
        $html .= '</div><!-- END ROW -->';
        $html .= '<h2>Jungle</h2>';
        $html .= '<div name="api_match_details_row" class="row">';
            $html .= '<div name="api_match_details_div" class="col-lg-6">';
                $html .= buildMatchLaneDetailTable("Jungle", $jungler);
            $html .= '</div><!-- END DIV -->';
        $html .= '</div><!-- END ROW -->';
    $html .= '<h2>Add Match</h2>';
    $html .= '<div name="api_add_match_row" class="row">';
    $html .= '<div name="api_add_match_div" class="col-lg-12">';
    if($temp_my_team == 100) {
        $html .= buildAddNewMatch($_SESSION['gameid'], $create_date, $blue_team2[$b_top], $blue_team2[$b_jungle],
            $blue_team2[$b_mid], $blue_team2[$b_adc], $blue_team2[$b_support],
            $purple_team2[$p_top], $purple_team2[$p_jungle], $purple_team2[$p_mid],
            $purple_team2[$p_adc], $purple_team2[$p_support], $outcome);
    } else {
        $html .= buildAddNewMatch($_SESSION['gameid'], $create_date, $purple_team2[$p_top], $purple_team2[$p_jungle],
            $purple_team2[$p_mid], $purple_team2[$p_adc], $purple_team2[$p_support],
            $blue_team2[$b_top], $blue_team2[$b_jungle], $blue_team2[$b_mid],
            $blue_team2[$b_adc], $blue_team2[$b_support], $outcome);
    }

    $html .= '</div><!-- END DIV -->';
    $html .= '</div><!-- END ROW -->';
    $html .= '</div><!-- END ACCORDION_ROW -->';
} else {

}
$html .= '<p></p><p><a onclick="back()" class="btn btn-primary btn-large" id="back_button" >Back</a></p><p></p>';

$blue_team_totalkills = getTeamTotalKills($blue_team2);
$purp_team_totalkills = getTeamTotalKills($purple_team2);

$toReturn = (object) array('html'=>'', 'data'=>'','fileNames'=>'');
$toReturn->html = $html;
$toReturn->data = array('team_totalKills'=>array((object) array('name'=>'team1','value'=>$blue_team_totalkills, 'color'=>'blue'),
    (object) array('name'=>'team2','value'=>$purp_team_totalkills, 'color'=>'purple')));


$tmpfname = tempnam("/var/www/", "FOO");
$handle = fopen($tmpfname, "w+");
$obj = json_encode(array((object) array('name'=>'team1','value'=>$blue_team_totalkills, 'color'=>'blue'),
    (object) array('name'=>'team2','value'=>$purp_team_totalkills, 'color'=>'purple')));
fwrite($handle, $obj);
fclose($handle);
$_SESSION['tempFileName'] = $tmpfname;
$toReturn->fileNames = (object) array('team_totalKillsfilename'=>$_SESSION['tempFileName']);
echo json_encode($toReturn);

$champion->CloseConnection();
$league_priority->CloseConnection();

function buildMatchDetails($gid, $league_obj, $host, $un, $pw, $db) {
    $string = "";
    $match = new LeagueMatchDetails($gid, $_SESSION['region'], $host, $un, $pw, $db);
    $string = json_decode($match->GetMatchDetails());
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
