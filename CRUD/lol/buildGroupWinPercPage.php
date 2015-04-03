<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 01/15/15
 * Time: 11:41 AM
 */

session_start();

include('../../CRUD/library/league.php');

$lol = new league();

$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$select_group = $lol->BuildGroupMap();
$final_html = '<div name="group_row" class="row">';
$final_html .= '<div name="group_sel_div" id="group_sel_div" class="col-lg-3">';
$final_html .= '<h3>Select Group ID: '.$select_group.'</h3> ';
$final_html .= '</div><!-- END group_sel_div -->';
$final_html .= '</div><!-- END group_row -->';
$final_html .= '<div name="group_details_row" id="group_details_row" class="row">';
$final_html .= '<div name="group_mems_div" id="group_mems_div" class="col-lg-3">';
$final_html .= '';
$final_html .= '</div><!-- END group_mems_div -->';
$final_html .= '<div name="group_mems_win_div" id="group_mems_win_div" class="col-lg-9">';
$final_html .= '</div><!-- END group_mems_win_div -->';
$final_html .= '</div><!-- END group_details_row -->';
echo $final_html;
$lol->CloseConnection();