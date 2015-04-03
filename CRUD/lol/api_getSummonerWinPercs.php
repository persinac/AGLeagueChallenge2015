<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 01/08/15
 * Time: 11:30 AM
 */

session_start();

include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/league.php');
include('../../CRUD/library/array_utilities.php');
include('../../CRUD/library/league_html_builder.php');

$lol = new League();
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

$t_sid = $_SESSION['summonerId'];

$final_html = '';

$top_win = $lol->GetWinPercentageByLane($t_sid, 1);
$jun_win = $lol->GetWinPercentageByLane($t_sid, 2);
$mid_win = $lol->GetWinPercentageByLane($t_sid, 3);
$adc_win = $lol->GetWinPercentageByLane($t_sid, 4);
$sup_win = $lol->GetWinPercentageByLane($t_sid, 5);

$final_html .= buildLaneWinPercentage($top_win, $jun_win, $mid_win, $adc_win, $sup_win);
$final_html .= "<p></p>";
$new_obj = $lol->GetTopChampWinPercentage($t_sid);
$final_html .= buildChampWinPercentage($new_obj);

echo $final_html;

$lol->CloseConnection();