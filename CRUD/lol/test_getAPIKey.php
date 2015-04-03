<?php require_once('../../Connections/lol_api_challenge_conn.php');
require_once('../../keys/key.php');?>
<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/03/15
 * Time: 2:31 PM
 */

session_start();
include('../../CRUD/library/riot_api.php');
$lol = new Buckets('na', $my_key, $lol_host, $lol_un, $lol_pw, $lol_db);
$final_html = "<h4> Group Members </h4>";
$final_html = "<p>No group members</p>";
$val = $lol->TestingOutKeyLocation();
$final_html .= '<p>KEY: ' . $val .'</p>';
echo $final_html;

$lol->CloseConnection();