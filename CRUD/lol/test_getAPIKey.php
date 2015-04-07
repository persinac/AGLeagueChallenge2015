<?php require_once('../../Connections/lol_api_challenge_conn.php');
require_once('../../keys/key.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/03/15
 * Time: 2:31 PM
 */

session_start();
include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/LeagueAPIChallenge.php');

$lol = new Buckets('na', $my_key, $lol_host, $lol_un, $lol_pw, $lol_db);
$my_db_operations = new LeagueAPIChallenge();
$date2 = date("Y-m-d H:i:s",strtotime(date("Y-m-d 09:00:00")));
$final_html .= '<p>Date: ' . $date2 .'</p>';
$val = strtotime($date2);
$final_html .= '<p>Date: ' . $val .'</p>';
$lol->SetBeginDate($val);
$matches = json_decode($lol->GetBucketOfMatches());
$final_html .= '<h3>Matches</h3>';
/** 1. Get max bucketID from table */
$bucketId = $my_db_operations->GetMaxBucketId() + 1;
for($i = 0; $i < sizeof($matches); $i++) {
    /**
     * 2. Insert new match based on matchID and current MAX bucketID
     */
    $curr_matchId = $matches[$i];
    $retVal = $my_db_operations->InsertNewBucket($bucketId, $curr_matchId, 'na');
    $final_html .= '<p>************************** BEGIN MATCH *******************************</p>';
    $final_html .= '<p>INSERT BUCKETID RETVAL: ' . $retVal . '</p>';
    $match = new LeagueMatchDetails($matches[$i], 'na', $my_key, $lol_host, $lol_un, $lol_pw, $lol_db);
    $final_html .= '<p>MatchID: ' . $matches[$i] . '</p>';
    $final_html .= '<p>URL:' . $match->GetURL() . '</p>';
    $string = json_decode($match->GetMatchDetails());
    $test_champ = $match->ParticipantDetails($string);
    $team_details = $match->TeamDetails($string);
    $final_html .= '<h4 style="text-indent: 50px;">Some Participant data: </h4>';
    $team_100 = '<p style="text-indent: 100px;">Team 100:</p>';
    $team_200 = '<p style="text-indent: 100px;">Team 200:</p>';
    foreach($test_champ AS $champ) {
        if($champ->GetTeamID() == 100) {
            $team_100 .= '<p style="text-indent: 100px;">Champion ID: '.$champ->GetChampionID().' </p>';
        } else {
            $team_200 .= '<p style="text-indent: 100px;">Champion ID: '.$champ->GetChampionID().' </p>';
        }
    }
    $final_html .= $team_100 . $team_200;
    $final_html .= '<h4 style="text-indent: 50px;">Some Team data: </h4>';
    $team_detail_string = "";
    foreach($team_details AS $team) {
        $final_html .= '<p style="text-indent: 100px;">Team ID: '.$team->teamId.' </p>';
        $final_html .= '<p style="text-indent: 100px;">Winner: '.$team->winner.' </p>';
        $final_html .= '<p style="text-indent: 100px;">First Tower: '.$team->firstTower.' </p>';
    }
    $final_html .= '<p>************************** END MATCH *******************************</p>';
    $match->CloseConnection();
}


echo $final_html;

$lol->CloseConnection();