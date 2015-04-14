<?php require_once('../../Connections/lol_api_challenge_conn.php');
require_once('../../keys/key.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/13/15
 * Time: 9:00 AM
 */

session_start();
include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/LeagueAPIChallenge.php');
$my_db_operations = new LeagueAPIChallenge('na', $lol_host, $lol_un, $lol_pw, $lol_db);
//$time_checker = new TimeChecker('na', $lol_host, $lol_un, $lol_pw, $lol_db);
//$retVal = $time_checker->GetTimeToUse()
$time_array = array();
$date_array = array();
$time = "";
for($h = 0; $h < 24; $h++) {
    for($m = 0; $m < 60; $m += 5) {
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

$date = "";
for($day = 5; $day < 13; $day++) {
    //date("Y-m-d");
    if($day < 10) {
        $date =  new DateTime('04/0'.$day.'/2015');
    } else {
        $date =  new DateTime('04/'.$day.'/2015');
    }
   // echo $date;
    $date_array[] = $date;
}

//var_dump($time_array);
//var_dump($date_array);

for($d = 0; $d < sizeof($date_array); $d++) {
    $formatted_date = date_format($date_array[$d], 'Y-m-d');
    //echo "<h4>DATE: " . $formatted_date . " </h4>";
    for ($u = 0; $u < sizeof($time_array); $u++) {
        $date2 = date("Y-m-d H:i:s", strtotime(date($formatted_date . " " . $time_array[$u] . "") . " GMT"));
        $val = strtotime($date2);
        //echo "<p>TIME: " . $time_array[$u] . ", EPOCH: " . $val . ", DATE: $date2</p>";
    }
}
echo "<h3>GETMATCHID: </h3>";
echo "<p>" . $my_db_operations->GetMatchID(65625359) . "</p>";
$my_db_operations->CloseConnection();
