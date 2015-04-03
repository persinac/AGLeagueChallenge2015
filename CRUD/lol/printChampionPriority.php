<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/19/14
 * Time: 10:34 AM
 */

session_start();


include('../../CRUD/library/riot_api.php');
include('../../CRUD/library/array_utilities.php');
include('../../CRUD/library/league.php');

$all_champs = new league();
$all_champs->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$retVal = $all_champs->BuildLanePriority();
$obj = $all_champs->GetLanePriority();

$final_html .= '<div class="row"">';
$build = "";
$build .= '<div name="all_champs_" class="col-lg-12">';
$build .= '<table id="list_of_champs" class="table table-striped">';
$build .= '<thead>';
$build .= '<tr>';
$build .= '<td> Champ ID </td>';
$build .= '<td> Name </td>';
$build .= '<td> Top Priority </td>';
$build .= '<td> Jungle Priority </td>';
$build .= '<td> Mid Priority </td>';
$build .= '<td> ADC Priority </td>';
$build .= '<td> Support Priority </td>';
$build .= '<td></td>';
$build .= '</tr>';
$build .= '</thead>';
$build .= '<tbody>';
foreach($obj AS $i=>$val) {
    $build .= '<tr>';
    $build .= '<td>'.$val->champ_id.'</td>';
    $build .= '<td>'.$val->champ_name.'</td>';
    $build .= '<td><input type="text" name="top_'.$val->champ_id.'" id="top_'.$val->champ_id.'" value="'.number_format((float)$val->tp, 2, '.', '').'" data-count="'.$val->tp_count.'" data-orig="'.number_format((float)$val->tp, 2, '.', '').'"/></td>';
    $build .= '<td><input type="text" name="jun_'.$val->champ_id.'" id="jun_'.$val->champ_id.'" value="'.number_format((float)$val->jp, 2, '.', '').'" data-count="'.$val->jp_count.'" data-orig="'.number_format((float)$val->jp, 2, '.', '').'"/></td>';
    $build .= '<td><input type="text" name="mid_'.$val->champ_id.'" id="mid_'.$val->champ_id.'" value="'.number_format((float)$val->mp, 2, '.', '').'" data-count="'.$val->mp_count.'" data-orig="'.number_format((float)$val->mp, 2, '.', '').'"/></td>';
    $build .= '<td><input type="text" name="adc_'.$val->champ_id.'" id="adc_'.$val->champ_id.'" value="'.number_format((float)$val->ap, 2, '.', '').'" data-count="'.$val->ap_count.'" data-orig="'.number_format((float)$val->ap, 2, '.', '').'"/></td>';
    $build .= '<td><input type="text" name="sup_'.$val->champ_id.'" id="sup_'.$val->champ_id.'" value="'.number_format((float)$val->sp, 2, '.', '').'" data-count="'.$val->sp_count.'" data-orig="'.number_format((float)$val->sp, 2, '.', '').'"/></td>';
    $build .= '<td><a onclick="updateLanePriority('.$val->champ_id.')" class="btn btn-primary btn-large" id="new_match_button" >Submit</a></td>';
    $build .= '</tr>';
}
$build .= '</tbody></table>';
$final_html .= $build;
$final_html .= '</div>';
echo $final_html;
$all_champs->CloseConnection();
function organizeChamps($arr) {
    $counter = 0;
    $build = "";
    $build .= '<div name="all_champs_" class="col-lg-12">';
    $build .= '<table id="list_of_champs" class="table table-striped">';
    $build .= '<thead>';
    $build .= '<tr>';
    $build .= '<td> Champ ID </td>';
    $build .= '<td> Name </td>';
    $build .= '<td> Top Priority </td>';
    $build .= '<td> Jungle Priority </td>';
    $build .= '<td> Mid Priority </td>';
    $build .= '<td> ADC Priority </td>';
    $build .= '<td> Support Priority </td>';
    $build .= '</tr>';
    $build .= '</thead>';
    $build .= '<tbody>';
    foreach($arr AS $i=>$val) {
        //$build .= '<p>';
        if(is_object($val)) {
            $build .= "***$val->id,";
            $build .= "'$val->name',";
            $build .= "0,";
            $build .= "0,";
            $build .= "0,";
            $build .= "0,";
            $build .= "0";
        }
        //$build .= '</p>';

    }

    return $build;
}