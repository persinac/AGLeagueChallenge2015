<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/12/14
 * Time: 9:15 AM
 */

session_start();

include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/league.php');
include('../../CRUD/library/array_utilities.php');
include('../../CRUD/library/league_html_builder.php');

$x = array(); //used to create json object to store in session variable
$summoner = new SummonerInfo("na", $lol_host, $lol_un, $lol_pw, $lol_db);
$recent_games = new LeagueGames("na", $lol_host, $lol_un, $lol_pw, $lol_db);
$champion = new LeagueChampions("na", $lol_host, $lol_un, $lol_pw, $lol_db);
//$champion->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

$lol = new League();
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

$name = $_POST['summoner'];
$_SESSION['region'] = "na";

$retVal = $summoner->SearchForSummonerByName($name);
$obj = json_decode($retVal);
$summonerLevel = 0;
foreach($obj AS $i=>$val) {
    if(is_object($val)) {
        $_SESSION['summonerId'] = $val->id;
        $summoner->SetSummonerID($val->id);
        $summonerLevel = $val->summonerLevel;
    }
}
$final_html = '';
$win_perc = '';
$retVal = $recent_games->GetSummonerRecentGames($summoner->GetSummonerID());
$obj = json_decode($retVal);
$list_of_game_ids = "";
foreach($obj AS $i=>$val) {
    if($i != 'summonerId') {
        $final_html .= '<div class="col-lg-12">';
        $final_html = "<h3>$name's Recent Games:</h3>";
        $final_html .= '<div class="recent_games_size">';
        $final_html .= '<table id="list_of_workouts" class="table table-striped table-hover">';
        $final_html .= '<thead><tr><th>Game Date</th>';
        $final_html .= '<th>Game Mode</th><th>Champion Played</th>';
        $final_html .= '<th>Team</th><th>Win/Loss</th><th>  </th></tr></thead>';
        $final_html .= '<tbody>';
        if(is_array($val)) {
            foreach($val AS $j=>$val2) {
                $det = (object) array('gameid' => '', 'game_mode' => '', 'win' => '',
                    'date'=>'', 'fellowPlayers'=>'', 'whoIPlayed'=>'', 'myteam'=>'', 'game_type'=>''
                ,'summonerid'=>$summoner->GetSummonerID(),'cameFromAPI'=>true);
                $list_of_game_ids .= $val2->gameId . ",";
                $final_html .= '<tr>';
                $t_game_id = $val2->gameId;
                //$final_html .= '<td><a onclick="api_matchDetails('.$t_game_id.')">' . convertTimeToDate($val2->createDate) .'</a></td>';
                $final_html .= '<td>' . convertTimeToDate($val2->createDate) .'</td>';
                $final_html .= '<td>'.$val2->gameMode.' - ' . $val2->gameType . '</td>';
                $obj2 = $champion->GetChampionByID($val2->championId);
                //var_dump($obj2);
                $final_html .= '<td>'.$obj2->name.'</td>';
                $final_html .= '<td>';
                if($val2->teamId == 100) {
                    $final_html .= 'BLUE';
                } else {
                    $final_html .= 'PURPLE';
                }
                $final_html .= '</td>';
                $final_html .= '<td>';
                if($val2->stats->win == 1) {
                    $final_html .= " <b>WIN</b> ";
                } else {
                    $final_html .= " <b>LOSS</b> ";
                }
                $final_html .= '</td>';
                $final_html .= '<td><a onclick="viewTeams('.$t_game_id.')">View Teams</a>';
                $final_html .= '</tr>';

                $det->gameid = $t_game_id;
                $det->game_mode = $val2->gameMode;
                $det->game_type = $val2->gameType;
                $det->win = $val2->stats->win;
                $det->date = convertTimeToDate($val2->createDate);
                $det->fellowPlayers = json_encode($val2->fellowPlayers);
                $det->whoIPlayed = $val2->championId;
                $det->myteam = $val2->teamId;
                $x[] = $det;

            }
        }
    }
}
$t = $lol->getMatchesNotReturnedFromAPI(substr($list_of_game_ids,0, strlen($list_of_game_ids)-1), $summoner->GetSummonerID());
for($i = 0; $i < sizeof($t); $i++) {
    $det = (object) array('gameid' => '', 'game_mode' => '', 'win' => '',
        'date'=>'', 'fellowPlayers'=>'', 'whoIPlayed'=>'', 'myteam'=>'', 'game_type'=>''
    ,'summonerid'=>$summoner->GetSummonerID(),'cameFromAPI'=>false);
    $final_html .= '<tr>';
    $t_game_id = $t[$i]->gameId;
    $final_html .= '<td>' . $t[$i]->createDate .'</td>';
    $final_html .= '<td>'.$t[$i]->gameMode.' - ' . $t[$i]->gameType . '</td>';
    $obj2 = $champion->GetChampionByID($t[$i]->champ_played);
    $final_html .= '<td>'.$obj2->name.'</td>';
    $final_html .= '<td>';
    if($t[$i]->teamId == 100) {
        $final_html .= 'BLUE';
    } else {
        $final_html .= 'PURPLE';
    }
    $final_html .= '</td>';
    $final_html .= '<td>';
    if($t[$i]->outcome == 1) {
        $final_html .= " <b>WIN</b> ";
    } else {
        $final_html .= " <b>LOSS</b> ";
    }
    $final_html .= '</td>';
    $final_html .= '<td><a onclick="viewTeams('.$t_game_id.')">View Teams</a>';
    $final_html .= '</tr>';

    $det->gameid = $t_game_id;
    $det->game_mode = $t[$i]->gameMode;
    $det->game_type = $t[$i]->gameType;
    $det->win = $t[$i]->outcome;
    $det->date = $t[$i]->createDate;
    //$det->fellowPlayers = json_encode($val2->fellowPlayers);
    $det->whoIPlayed = $t[$i]->champ_played;
    $det->myteam = $val2->teamId;
    $x[] = $det;
}

$final_html .= '</tbody></table></div></div>';

$final_html .= '<div name="win_perc_breakdown" id="win_perc_breakdown" class="row">';
$final_html .= '<h3>Win % Breakdown</h3>';
$final_html .= '</div> <!-- END win_perc_breakdown -->';
$final_html .= '<div>
<a onclick="displaySummonerNetwork()"
    class="btn btn-primary btn-large"
    id="test_sum_network" >
    Summoner Network
    </a>
</div>';

echo $final_html;
$_SESSION['game_details'] = $x;

$skeleton = '<div name="api_test_row" class="row">';
        $skeleton .= '<div name="api_test_1" class="col-lg-6">';
            $skeleton .= '<b>Summoner Name:</b> <input type="text" name="summoner_to_find" id="summoner_to_find"/> ';
            $skeleton .= '<a onclick="getSummonerDetails()" class="btn btn-primary btn-large" id="new_match_button" >Submit</a>';
        $skeleton .= '</div>';
    $skeleton .= '</div>';
    $skeleton .= '<div name="api_recent_games_row" class="row">';
        $skeleton .= '<div name="api_recent_games_div" id="api_recent_games_div" class="col-lg-12">';
        $skeleton .= $final_html;
        $skeleton .= '</div>';
    $skeleton .= '</div>';
    $skeleton .= '<div name="api_test_match_row" class="row">';
        $skeleton .= '<div name="api_test_match_details" id="api_test_match_details" class="col-lg-12">';
        $skeleton .= '</div>';
    $skeleton .= '</div>';

$_SESSION['prev_page'] = $skeleton;
$lol->CloseConnection();
$champion->CloseConnection();