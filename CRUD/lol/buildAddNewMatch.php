<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/03/14
 * Time: 3:27 PM
 */

/**
 * @param $create_date
 * @param $sum_top
 * @param $sum_jun
 * @param $sum_mid
 * @param $sum_adc
 * @param $sum_sup
 * @param $c_our_top
 * @param $c_our_jun
 * @param $c_our_mid
 * @param $c_our_adc
 * @param $c_our_sup
 * @param $c_ene_top
 * @param $c_ene_jun
 * @param $c_ene_mid
 * @param $c_ene_adc
 * @param $c_ene_sup
 * @param $outcome
 * @return string
 */
function buildAddNewMatch($gameId, $create_date, $c_our_top, $c_our_jun, $c_our_mid, $c_our_adc, $c_our_sup
                            , $c_ene_top, $c_ene_jun, $c_ene_mid, $c_ene_adc, $c_ene_sup
                            , $outcome) {
    $final_html = '<form id="new_match">';
    $final_html .= '<div name="required_content_1" class="row">';
    $final_html .= '<h4>Required:</h4>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Game ID: </b><input type="text" name="gameId" id="gameId" value="'.$gameId.'" readonly/><p></p>';
    $final_html .= '<b>Outcome: </b>';
    $final_html .= '<select name="outcome" id="outcome">';
    if($outcome == 1) {
        $final_html .= '<option value="1"> Win </option><option value="0"> Loss </option>';
    } else {
        $final_html .= '<option value="0"> Loss </option><option value="1"> Win </option>';
    }
    $final_html .= '</select>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Date: </b><input type="text" name="date" id="date" value="'.$create_date.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>FF @ 20?: </b>';
    $final_html .= '<select name="ff" id="ff"><option value="0"> NO </option><option value="1"> YES </option></select>';
    $final_html .= '</div>';
    $final_html .= '</div>';
    $final_html .= '<div name="required_content_2" class="row">';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>My team # of skins: </b>';
    $final_html .= '<select name="my_team" id="my_team"><option value="0"> 0 </option><option value="1"> 1 </option>';
    $final_html .= '<option value="2"> 2 </option><option value="3"> 3 </option>';
    $final_html .= '<option value="4"> 4 </option><option value="5"> 5 </option>';
    $final_html .= '</select>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Other team # of skins: </b>';
    $final_html .= '<select name="other_team" id="other_team"><option value="0"> 0 </option><option value="1"> 1 </option>';
    $final_html .= '<option value="2"> 2 </option><option value="3"> 3 </option>';
    $final_html .= '<option value="4"> 4 </option><option value="5"> 5 </option>';
    $final_html .= '</select>';
    $final_html .= '</div>';
    $final_html .= '</div>';
    $final_html .= '<div name="optional_content_hdr" class="row">';
    $final_html .= '<h4>Optional - Who played Where?</h4>';
    $final_html .= '</div>';
    $final_html .= '<div name="optional_content_dtl" class="row">';
    /* TOP LANE */
    $final_html .= '<div name="optional_content_dtl_top" class="row">';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Top: </b>';
    $final_html .= '<input type="text" name="s_our_top_input" id="s_our_top_input" value="'.$c_our_top->summonerID.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Champion: </b>';
    $final_html .= '<input type="text" name="c_our_top_input" id="c_our_top_input" value="'.$c_our_top->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Opponent: </b>';
    $final_html .= '<input type="text" name="c_ene_top_input" id="c_ene_top_input" value="'.$c_ene_top->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '</div>';
    $final_html .= '<p></p>';
    /* MID LANE */
    $final_html .= '<div name="optional_content_dtl_mid" class="row">';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Mid: </b>';
    $final_html .= '<input type="text" name="s_our_mid_input" id="s_our_mid_input" value="'.$c_our_mid->summonerID.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Champion: </b>';
    $final_html .= '<input type="text" name="c_our_mid_input" id="c_our_mid_input" value="'.$c_our_mid->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Opponent: </b>';
    $final_html .= '<input type="text" name="c_ene_mid_input" id="c_ene_mid_input" value="'.$c_ene_mid->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '</div>';
    $final_html .= '<p></p>';
    /* JUNGLE */
    $final_html .= '<div name="optional_content_dtl_jun" class="row">';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Jungle: </b>';
    $final_html .= '<input type="text" name="s_our_jun_input" id="s_our_jun_input" value="'.$c_our_jun->summonerID.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Champion: </b>';
    $final_html .= '<input type="text" name="c_our_jun_input" id="c_our_jun_input" value="'.$c_our_jun->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Opponent: </b>';
    $final_html .= '<input type="text" name="c_ene_jun_input" id="c_ene_jun_input" value="'.$c_ene_jun->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '</div>';
    $final_html .= '<p></p>';
    /* SUPPORT */
    $final_html .= '<div name="optional_content_dtl_sup" class="row">';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Support: </b>';
    $final_html .= '<input type="text" name="s_our_sup_input" id="s_our_sup_input" value="'.$c_our_sup->summonerID.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Champion: </b>';
    $final_html .= '<input type="text" name="c_our_sup_input" id="c_our_sup_input" value="'.$c_our_sup->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Opponent: </b>';
    $final_html .= '<input type="text" name="c_ene_sup_input" id="c_ene_sup_input" value="'.$c_ene_sup->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '</div>';
    $final_html .= '<p></p>';
    /* ADC */
    $final_html .= '<div name="optional_content_dtl_adc" class="row">';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>ADC: </b>';
    $final_html .= '<input type="text" name="s_our_adc_input" id="s_our_adc_input" value="'.$c_our_adc->summonerID.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Champion: </b>';
    $final_html .= '<input type="text" name="c_our_adc_input" id="c_our_adc_input" value="'.$c_our_adc->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>Opponent: </b>';
    $final_html .= '<input type="text" name="c_ene_adc_input" id="c_ene_adc_input" value="'.$c_ene_adc->name.'"/>';
    $final_html .= '</div>';
    $final_html .= '</div>';

    $final_html .= '<div class="col-lg-4">';
    $final_html .= '<b>First game of the night? </b>';
    $final_html .= '<select name="first_game" id="first_game">';
    $final_html .= '<option value="0"> No </option><option value="1"> Yes </option>';
    $final_html .= '</select>';
    $final_html .= '</div>';

    $final_html .= '</div>';
    $final_html .= '</form><p></p>';
    $final_html .= '<a onclick="updateMatch()" class="btn btn-primary btn-large" id="new_match_button" >Submit</a>';

    return $final_html;
}