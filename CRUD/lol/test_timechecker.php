<?php require_once('../../Connections/lol_api_challenge_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/13/15
 * Time: 9:00 AM
 */

session_start();
include('../../CRUD/library/LeagueAPIChallenge.php');
$time_checker = new TimeChecker('na', $lol_host, $lol_un, $lol_pw, $lol_db);
$retVal = $time_checker->GetTimeToUse();
$time_array = array();
$time = "";
for($h = $retVal->hour; $h < $retVal->hour + 1; $h++) {
    for($m = $retVal->minute; $m < $retVal->minute + 30; $m += 5) {
        if($h < 10) {
            $time = "0" . $h . ":";
        } else {
            $time = $h . ":";
        }
        if($m < 10) {
            $time .= "0" . $m . ":00";
        } else {
            $time .= $m . ":00";
        }
        $time_array[] = $time;
    }
}

var_dump($time_array);
$time_checker->CloseConnection();