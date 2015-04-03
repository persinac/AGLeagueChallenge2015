<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 01/15/15
 * Time: 1:21 PM
 */

session_start();
include('../../CRUD/library/league.php');
$groupid = $_POST['groupid'];
$lol = new league();
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$final_html = "<h4> Group Members </h4>";

if($lol->BuildGroupMembers($groupid) == 1) {
    $final_html .= '<table class="table table-striped>"';
    $final_html .= '<tbody>';
    $det = $lol->GetGroupMembers();
    foreach($det AS $key => $val) {
        $final_html .= '<tr><td>'.$val->summonerName . ' </td>';
        $final_html .= '<td><input type="checkbox" name="summoner" class="summoner" value="'.$val->summonerId.'"></td></tr>';
    }
    $final_html .= '</tbody>';
} else {
    $final_html = "<p>No group members</p>";
}
echo $final_html;

$lol->CloseConnection();
