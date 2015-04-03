<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 01/05/15
 * Time: 1:15 PM
 */
session_start();
include('../../CRUD/library/league.php');
$obj = $_SESSION['game_details'];

$lol = new League();
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

for($i = 0; $i < sizeof($obj); $i++) {
    $retVal = $lol->doesMatchIDExist($obj[$i]->gameid, $obj[$i]->summonerid);
    if($retVal == true) {
    } else {
        echo $lol->InsertMatchHistory($obj[$i]->gameid, $obj[$i]->game_type, $obj[$i]->game_mode,
            $obj[$i]->date, $obj[$i]->whoIPlayed, $obj[$i]->myteam, $obj[$i]->win, $obj[$i]->summonerid);
    }
}
$lol->CloseConnection();
