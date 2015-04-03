<?php require_once('/var/www/Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/31/14
 * Time: 10:43 AM
 */
session_start();


include('/var/www/CRUD/library/riot_api.php');
include('/var/www/CRUD/library/league.php');


$lol = new league();
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$summonerInfo_obj = new SummonerInfo("na", $lol_host, $lol_un, $lol_pw, $lol_db);

$date = date("Y-m-d H:i:s");
$url = "NO_URL";
$data = "Starting CRON Job...";

$summonerInfo_obj->insertIntoAPILog($url,$date,$data);

$contents = file_get_contents("/var/www/listOfSummoners.txt");

$new_array = explode("\n", $contents);
$count = 1;
while(sizeof($new_array) > 0) {
    echo parseFileContents($new_array, $count, $summonerInfo_obj, $lol);
    sleep(11); // wait 11 seconds
    $count++;
}

$txt = "";
file_put_contents("/var/www/listOfSummoners.txt", $txt);
$lol->CloseConnection();

function parseFileContents(&$arr, $c, $summ_obj, $lol_obj) {
    for($i = 0; $i < 9; $i++) {
        $ele = array_pop($arr);
        if(strlen($ele) > 0) {
            $new_summ = json_decode($summ_obj->SearchForSummonerByID($ele, 1));
            $lol_obj->InsertNewPlayer($ele, $new_summ->$ele->name);
        }
    }
}
