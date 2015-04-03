<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/04/14
 * Time: 3:58 PM
 */

session_start();
/*
if(!(isset($_SESSION['MM_Username'])))
{
    header("Location: Error401UnauthorizedAccess.php");
}*/

include('../../CRUD/library/league.php');
$matchid = $_POST['matchid'];
$lol = new league();

$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$lol->BuildMatchDetails($matchid);
$det = $lol->GetMatchDetails();
foreach($det AS $key => $val) {
    //echo 'FOREAVCH: '.$key . ', ' . $val->matchid;
    $final_html = '<div name="match_dtl_header_row" class="row">';
    $final_html .= '<div class="col-lg-12">';
    $final_html .= '<h3><b>Match Details</b></h3>';
    $final_html .= '</div>';
    $final_html .= '</div> <!-- END match_dtl_header_row -->';
    $final_html .= '<div name="match_dtl_1_row" class="row">';
    $final_html .= '<div name="match_dtl_div_a" class="col-lg-12">';
    $final_html .= '<h4>Date: ' . $val->date . '</h4>';
    $final_html .= '<h4>First game of the Night? ';
    if($val->first_game == 1) {
        $final_html .= '<b> Yes </b>';
    } else {
        $final_html .= '<b> No </b>';
    }
    $final_html .= '</h4>';
    $final_html .= '<h4>Outcome: ';
    if($val->outcome == 1) {
        $final_html .= '<b>Win</b>';
    } else {
        $final_html .= '<b>Loss</b>';
    }
    $final_html .= '</h4>';
    $final_html .= '<h4> Forfeit @ 20? ';
    if($val->ff == 1) {
        $final_html .= '<b>Yes</b>';
    } else {
        $final_html .= '<b>No</b>';
    }
    $final_html .= '</h4>';
    $final_html .= '</div><!--  END match_dtl_div_a -->';
    $final_html .= '</div>'; //end match_dtl_1_row
    $final_html .= '<div name="match_dtl_2_row" class="row">';
    $final_html .= '<div name="match_dtl_div_b" class="col-lg-9">';
    $final_html .= '<table id="list_all_matches" class="table table-striped table-hover">';
    $final_html .= '<thead>';
    $final_html .= '<th>   </th>';
    $final_html .= '<th> Top </th>';
    $final_html .= '<th> Mid </th>';
    $final_html .= '<th> Jungle </th>';
    $final_html .= '<th> Support </th>';
    $final_html .= '<th> ADC </th></tr>';
    $final_html .= '</thead>';
    $final_html .= '<tbody>';

    $final_html .= '<tr>';
    $final_html .= '<td> <b>Our players</b> </td>';
    $final_html .= '<td>' . $val->top_player .'</td>';
    $final_html .= '<td>' . $val->mid_player .'</td>';
    $final_html .= '<td>' . $val->jungle_player .'</td>';
    $final_html .= '<td>' . $val->support_player .'</td>';
    $final_html .= '<td>' . $val->adc_player .'</td>';
    $final_html .= '</tr>';
    $final_html .= '<tr>';
    $final_html .= '<td> <b>Our team comp</b> </td>';
    $final_html .= '<td>' . $val->c_our_top .'</td>';
    $final_html .= '<td>' . $val->c_our_mid .'</td>';
    $final_html .= '<td>' . $val->c_our_jun .'</td>';
    $final_html .= '<td>' . $val->c_our_sup .'</td>';
    $final_html .= '<td>' . $val->c_our_adc .'</td>';
    $final_html .= '</tr>';
    $final_html .= '<td> <b>Their team comp</b> </td>';
    $final_html .= '<td>' . $val->c_ene_top .'</td>';
    $final_html .= '<td>' . $val->c_ene_mid .'</td>';
    $final_html .= '<td>' . $val->c_ene_jun .'</td>';
    $final_html .= '<td>' . $val->c_ene_sup .'</td>';
    $final_html .= '<td>' . $val->c_ene_adc .'</td>';
    $final_html .= '</tr>';
    $final_html .= '</tbody>';
    $final_html .= '</table>';
    $final_html .= '</div>'; //end all_matches_div
    $final_html .= '</div>'; //end match_dtl_2_row
}
echo $final_html;
$lol->CloseConnection();