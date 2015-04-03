<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/15/14
 * Time: 2:55 PM
 */
session_start();
/*
if(!(isset($_SESSION['MM_Username'])))
{
    header("Location: Error401UnauthorizedAccess.php");
}*/

include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/array_utilities.php');

$all_champs = new LeagueChampions("na", $lol_host, $lol_un, $lol_pw, $lol_db);

$retVal = $all_champs->PrintAllChampions();

$obj = json_decode($retVal);

foreach($obj AS $i=>$val) {
    $final_html .= '<div class="row"">';
    if(is_array($val)) {
        $final_html .= organizeChamps(alphabetizeChampions($val));
    } else if(is_object($val)) {
        $final_html .= organizeChamps(alphabetizeChampions($val));
    }
    $final_html .= '</div>';
}
echo $final_html;

function organizeChamps($arr) {
    $counter = 0;
    $build = "";
    $build .= '<div name="all_champs_" class="col-lg-12">';
    $build .= '<table id="list_of_champs" class="table table-striped">';
    $build .= '<tbody>';
    foreach($arr AS $i=>$val) {
        if($counter % 4 == 0 ) {
            $build .= '<tr>';
        }

        if(is_object($val)) {
            $build .= '<td>';
            $build .= "<a><b>$val->name:</b></a>";
            $build .= "<p>ID: $val->id</p>";
            $build .= "<p>Title: $val->title</p>";
            $build .= "</td>";
        }

        if($counter % 4 == 3) {
            $build .= '</tr>';
        }
        $counter++;
    }
    $build .= '</tbody></table></div>';
    return $build;
}