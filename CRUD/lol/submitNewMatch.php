<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/03/14
 * Time: 4:11 PM
 */

session_start();

include('../../CRUD/library/league.php');
$lol = new league();
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

$gameId = $_POST['gameId'];
$outcome = $_POST['outcome'];
$date = $_POST['date'];
$ff = $_POST['ff'];
$my_team = $_POST['my_team'];
$other_team = $_POST['other_team'];
$first_game = $_POST['first_game'];
$top = $_POST['s_our_top_input'];
$mid = $_POST['s_our_mid_input'];
$jung = $_POST['s_our_jun_input'];
$supp = $_POST['s_our_sup_input'];
$adc = $_POST['s_our_adc_input'];

$c_our_top_input = $_POST['c_our_top_input'];
$c_our_mid_input = $_POST['c_our_mid_input'];
$c_our_jun_input = $_POST['c_our_jun_input'];
$c_our_sup_input = $_POST['c_our_sup_input'];
$c_our_adc_input = $_POST['c_our_adc_input'];

$c_ene_top_input = $_POST['c_ene_top_input'];
$c_ene_mid_input = $_POST['c_ene_mid_input'];
$c_ene_jun_input = $_POST['c_ene_jun_input'];
$c_ene_sup_input = $_POST['c_ene_sup_input'];
$c_ene_adc_input = $_POST['c_ene_adc_input'];

$c_our_top_id = $lol->getChampID($c_our_top_input);
$c_our_mid_id = $lol->getChampID($c_our_mid_input);
$c_our_jun_id = $lol->getChampID($c_our_jun_input);
$c_our_sup_id = $lol->getChampID($c_our_sup_input);
$c_our_adc_id = $lol->getChampID($c_our_adc_input);

$c_ene_top_id = $lol->getChampID($c_ene_top_input);
$c_ene_mid_id = $lol->getChampID($c_ene_mid_input);
$c_ene_jun_id = $lol->getChampID($c_ene_jun_input);
$c_ene_sup_id = $lol->getChampID($c_ene_sup_input);
$c_ene_adc_id = $lol->getChampID($c_ene_adc_input);

/*echo "$c_our_top_id,
$c_our_mid_id,
$c_our_jun_id,
$c_our_sup_id,
$c_our_adc_id,
$c_ene_top_id,
$c_ene_mid_id,
$c_ene_jun_id,
$c_ene_sup_id,
$c_ene_adc_id";*/


$retVal = $lol->InsertNewMatch($gameId, $outcome, $date, $ff, $my_team, $other_team, $first_game,
        $top, $mid, $jung,$supp,$adc, $c_our_top_id,
$c_our_mid_id,
$c_our_jun_id,
$c_our_sup_id,
$c_our_adc_id,
$c_ene_top_id,
$c_ene_mid_id,
$c_ene_jun_id,
$c_ene_sup_id,
$c_ene_adc_id);

echo $retVal;
$lol->CloseConnection();