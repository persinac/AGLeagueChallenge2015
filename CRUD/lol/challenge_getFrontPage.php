<?php require_once('../../Connections/lol_api_challenge_conn.php');
require_once('../../keys/key.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 04/12/15
 * Time: 10:44 AM
 */

session_start();

include('../../CRUD/library/LeagueAPIChallenge.php');

$lol = new LeagueAPIChallenge('na', $lol_host, $lol_un, $lol_pw, $lol_db);

$final_html = "";

$retVal = $lol->GetFirstPageStats();
$final_html = '<div name="all_matches_row" class="row ">';
$final_html .= '<div name="match_header_div" id="match_header_div" class="col-lg-7 ">';
$final_html .= '<div class="main_match_table">';
$final_html .= '<table id="list_all_matches" class="table table-striped table-hover">';
$final_html .= '<thead class="header_bg">';
$final_html .= '<th>Bucket ID</th>';
$final_html .= '<th>Match ID</th>';
$final_html .= '<th>Region</th>';
$final_html .= '<th>Match Creation</th>';
$final_html .= '<th>Match Duration</th>';
$final_html .= '</thead>';
$final_html .= '<tbody>';

for($i = 0; $i < sizeof($retVal); $i++) {
    $final_html .= '<tr>';
    $final_html .= '<td><a onclick="bucketDetails('.$retVal[$i]->bucketId.')">' . $retVal[$i]->bucketId .'</a></td>';
    $final_html .= '<td><a onclick="matchDetails('.$retVal[$i]->matchId.')">' . $retVal[$i]->matchId .'</a></td>';
    $final_html .= '<td>'.$retVal[$i]->region.'</td>';
    $final_html .= '<td>' . epochToDate($retVal[$i]->matchCreation) . '</td>';
    $final_html .= '<td>' . gmdate("H:i:s", $retVal[$i]->matchDuration) . '</td>';
    $final_html .= '</tr>';
}



$final_html .= '</tbody></table>';
$final_html .= '</div><!-- End recent_games_size -->';
$final_html .= '</div><!-- End HEADER_DIV -->';
$final_html .= '<div name="match_extras_div" class="col-lg-5">';
$final_html .= '<h4>Minions Sacrificed: '.$lol->GetMinionKills().'</h4>';
$final_html .= '<h4>Neutral Minions Terrorized: '.$lol->GetNeutralMinionKills().'</h4>';
$final_html .= '<h4>Number of times Urgot was picked: '.$lol->GetNumOfTimeUrgotPicked().'</h4>';
$final_html .= '<h4>Number of times Urgot was banned: '.$lol->GetNumOfTimeUrgotBanned().'</h4>';
$final_html .= '<h4>Number of times Urgot won: '.$lol->GetNumOfTimeUrgotWon().'</h4>';
$final_html .= '</div><!-- End match_extras_div -->';
$final_html .= '</div> <!-- END all_matches_row -->';

$lol->CloseConnection();

echo $final_html;

function formatMilliseconds($milliseconds) {
    $seconds = floor($milliseconds / 1000);
    $minutes = floor($seconds / 60);
    $hours = floor($minutes / 60);
    $milliseconds = $milliseconds % 1000;
    $seconds = $seconds % 60;
    $minutes = $minutes % 60;

    $format = '%u:%02u:%02u.%03u';
    $time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
    return rtrim($time, '0');
}

function epochToDate($epoch) {
    $dt = new DateTime("@$epoch");  // convert UNIX timestamp to PHP DateTime
    return $dt->format('Y-m-d H:i:s'); // output = 2012-08-15 00:00:00
}
