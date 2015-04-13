<?php require_once('../../Connections/lol_api_challenge_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/13/15
 * Time: 12:05 PM
 */

session_start();

include('../../CRUD/library/LeagueAPIChallenge.php');
set_time_limit(0);

$time_checker = new TimeChecker('na', $lol_host, $lol_un, $lol_pw, $lol_db);
$retVal = $time_checker->ResetTimeTable();
echo $retVal;
$time_checker->CloseConnection();