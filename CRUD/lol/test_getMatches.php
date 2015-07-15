<?php require_once('../../Connections/lol_api_challenge_conn.php');
require_once('../../keys/key.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/16/15
 * Time: 4:02 PM
 */

/*
 * 1793941250
 */

session_start();
include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/LeagueAPIChallenge.php');
set_time_limit(0);
$lol = new Buckets('na', $my_key, $lol_host, $lol_un, $lol_pw, $lol_db);
$my_db_operations = new LeagueAPIChallenge('na', $lol_host, $lol_un, $lol_pw, $lol_db);
$time_array = array();
$starting_matchId = 1793941250; //this will eventually be a Get() from my DB
$bucketId = $my_db_operations->GetMaxBucketId() + 1;

for($bId = $bucketId; $bId < $bucketId + 5; $bId++) {
    $curr_matchId = -1;
    echo "<h3>BUCKETID: $bId</h3>";
    for($t_mId = $starting_matchId + 1; $t_mId <  $starting_matchId + 5; $t_mId++) {
        $curr_matchId = $t_mId;
        echo "<p>Current MatchID: $curr_matchId</p>";
        $match = new LeagueMatchDetails($curr_matchId, 'na', $my_key, $lol_host, $lol_un, $lol_pw, $lol_db);
        $match_specs = $match->GetMatchDetails();
        if(strpos($match_specs, "HTTP ERROR") > -1) {
            echo "<p>Match not found</p>";
        } else {
            $match_specs = json_decode($match_specs);
            if($match_specs->status->status_code == 429) {
                echo "<p>Rate limit exceeded</p>";
            } else {
                echo "<p>Match found and within rate limit</p>";
            }
        }
        //echo "<p>Length of match string: " . strlen($match->GetMatchDetails()) . " </p>";
    }
    echo "<p> </p>";
    $starting_matchId = $curr_matchId;
}
echo "<p>FINISHED</p>";

$my_db_operations->CloseConnection();
$lol->CloseConnection();