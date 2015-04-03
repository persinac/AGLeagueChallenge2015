<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/10/14
 * Time: 1:58 PM
 */
session_start();
/*
if(!(isset($_SESSION['MM_Username'])))
{
    header("Location: Error401UnauthorizedAccess.php");
}*/
include('../../CRUD/library/league.php');
$lol = new league();
$term = $_GET['term'];
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$retVal = $lol->BuildChampionsForAutoComplete($term);

if($retVal == 1) {
    //$suggestions = array('suggestions'=>$lol->GetListOfChampions());
    echo json_encode($lol->GetListOfChampions());
}

/*
{
    "suggestions": [
        { "value": "United Arab Emirates", "data": "AE" },
        { "value": "United Kingdom",       "data": "UK" },
        { "value": "United States",        "data": "US" }
    ]
}

 */

$lol->CloseConnection();