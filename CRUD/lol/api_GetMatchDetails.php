<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/13/14
 * Time: 4:08 PM
 */
session_start();
/*
if(!(isset($_SESSION['MM_Username'])))
{
    header("Location: Error401UnauthorizedAccess.php");
}*/
include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/array_utilities.php');

if(isset($_POST['gameid'])) {
    $gameid = $_POST['gameid'];
} else if(isset($_SESSION['gameid'])) {
    $gameid = $_POST['gameid'];
} else {
    $gameid = -1;
}

$details = new LeagueMatchDetails($gameid, $_SESSION['region'], $lol_host, $lol_un, $lol_pw, $lol_db);
//$details->SetRegion($_SESSION['region']);
$det = json_decode($details->GetMatchDetails());
$html = '';

foreach($det AS $i=>$val) {
    $html .= "<p><b>$i</b></p>";
}

foreach($det AS $i=>$val) {
    if(strpos($i, 'participant') !== false) {
        $html .= "<h4>$i</h4><p>" . iterateArray($val, "") . "</p>";
    } else if(strpos($i,'teams') !== false) {
        $html .= "<h4>$i</h4><p>" . iterateArray($val, "") . "</p>";
    } else {
        $html .= "<p>$i: ";
        if ($i == 'matchCreation') {
            $html .= convertTimeToDate($val) . " ";
        } else if ($i == 'matchDuration') {
            $html .= makeReadableTime($val) . " ";
        } else {
            $html .= "$val ";
        }
        $html .= "</p>";
    }

}
echo $html;

