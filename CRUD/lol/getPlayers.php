<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/03/14
 * Time: 4:39 PM
 */

session_start();
/*
if(!(isset($_SESSION['MM_Username'])))
{
    header("Location: Error401UnauthorizedAccess.php");
}*/

include('../../CRUD/library/league.php');
$lol = new league();

$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);

$lol->BuildPlayerArray();
$det = $lol->GetListOfPlayers();

$final_html = '<div name="all_players_row" class="row">';
$final_html .= '<h4>Search: </h4><input type="text" onkeyup="summonerFinder()" id="summoner_search">';
$final_html .= '<div name="all_players_div" class="col-lg-12">';
$final_html .= '<table id="all_players" class="table table-striped table-hover">';
    $final_html .= '<thead>';
        $final_html .= '<th>Player ID</th>';
        $final_html .= '<th>Name</th>';
        $final_html .= '<th>League Name</th></tr>';
    $final_html .= '</thead>';
    $final_html .= '<tbody>';
foreach($det AS $key => $val) {
    $final_html .= '<tr data-name="'.$val->lol_name.'">';
    $final_html .= '<td>' . $val->player_id . '</td>';
    $final_html .= '<td>' . $val->first_name . ' ' . $val->last_name . '</td>';
    $final_html .= '<td><a onclick="getSummonerDetails(\''.$val->lol_name.'\')">' . $val->lol_name . '</a></td>';
    $final_html .= '</tr>';
}
$final_html .= '</tbody>';
$final_html .= '</table>';
$final_html .= '</div>'; //end all_players_div
$final_html .= '</div>'; //end all_players_row

echo $final_html;
$lol->CloseConnection();