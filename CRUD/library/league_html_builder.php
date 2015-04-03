<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/23/14
 * Time: 1:13 PM
 */

/*
 * Builds the lane tables
 *
 * parameters:
 *  $lane - string value of the lane
 *  $arr - array of Champions
 *  $role - OPTIONAL. May be used later to differentiate between support and adc
 *
 *  returns: string html table
 */
function buildMatchLaneDetailTable($lane, $arr, $role = "") {
    $html = "";

    if(strlen($role) > 1) {
        $lane = $role;
    }
    $html .= '<h3><span style="text-decoration:underline;">'.$lane.'</span></h3>';
    $html .= '<table id="lane_details_top" class="table table-striped table-hover">';
    $html .= '<thead><tr><td class="text-center"><h4>'.$arr[0]->name.'</h4></td><td>  </td><td class="text-center">
            <h4>'.$arr[1]->name.'</h4></td>';
    $html .= '</tr></thead>';
    $html .= '<tbody>';
    $html .= "<tr><td class=\"text-center blue_team\"> ".$arr[0]->cs." </td><td class=\"text-center \"> CS </td>
        <td class=\"text-center purple_team\"> ".$arr[1]->cs." </td></tr>";
    $html .= "<tr><td class=\"text-center blue_team\"> ".$arr[0]->kills." </td><td class=\"text-center\"> Kills </td>
        <td class=\"text-center purple_team\"> ".$arr[1]->kills." </td></tr>";
    $html .= "<tr><td class=\"text-center blue_team\"> ".$arr[0]->deaths." </td><td class=\"text-center\"> Deaths </td>
        <td class=\"text-center purple_team\"> ".$arr[1]->deaths." </td></tr>";
    $html .= "<tr><td class=\"text-center blue_team\"> ".$arr[0]->assists." </td><td class=\"text-center\"> Assists </td>
        <td class=\"text-center purple_team\"> ".$arr[1]->assists." </td></tr>";
    $html .= "<tr><td class=\"text-center blue_team\"> ".$arr[0]->goldearned." </td><td class=\"text-center\"> Gold Earned </td>
        <td class=\"text-center purple_team\"> ".$arr[1]->goldearned." </td></tr>";
    $html .= '</tbody></table>';

    return $html;
}

function buildTeamDetailTable($arr) {
    $html = "";

    $html .= '<h3><span style="text-decoration:underline;">Stats</span></h3>';
    $html .= '<table id="team_details_top" class="table table-striped table-hover">';
    $html .= '<thead><tr><td class="text-center"><h4> Blue Team </h4></td><td>  </td><td class="text-center">
            <h4> Purple Team </h4></td>';
    $html .= '</tr></thead>';
    $html .= '<tbody>';
    $html .= "<tr><td class=\"text-center blue_team\"> ".$arr[0]->baronKills." </td><td class=\"text-center \"> Baron Kills </td>
        <td class=\"text-center purple_team\"> ".$arr[1]->baronKills." </td></tr>";
    $html .= "<tr><td class=\"text-center blue_team\"> ".$arr[0]->dragonKills." </td><td class=\"text-center\"> Dragon Kills </td>
        <td class=\"text-center purple_team\"> ".$arr[1]->dragonKills." </td></tr>";

    //First blood
    $html .= "<tr><td class=\"text-center blue_team\"> ";
    if($arr[0]->firstBlood == 1) {
        $html .= "<b>X</b>";
    }
    $html .= " </td><td class=\"text-center\"> First Blood </td><td class=\"text-center purple_team\"> ";
    if($arr[1]->firstBlood == 1) {
        $html .= "<b>X</b>";
    }
    $html .= " </td></tr>";

    //First dragon
    $html .= "<tr><td class=\"text-center blue_team\"> ";
    if($arr[0]->firstDragon == 1) {
        $html .= "<b>X</b>";
    }
    $html .= " </td><td class=\"text-center\"> First Dragon </td><td class=\"text-center purple_team\"> ";
    if($arr[1]->firstDragon == 1) {
        $html .= "<b>X</b>";
    }
    $html .= " </td></tr>";

    //First baron
    $html .= "<tr><td class=\"text-center blue_team\"> ";
    if($arr[0]->firstBaron == 1) {
        $html .= "<b>X</b>";
    }
    $html .= " </td><td class=\"text-center\"> First Baron </td><td class=\"text-center purple_team\"> ";
    if($arr[1]->firstBaron == 1) {
        $html .= "<b>X</b>";
    }
    $html .= " </td></tr>";
    $html .= '</tbody></table>';

    return $html;
}

/**
 * @param $top
 * @param $jungle
 * @param $mid
 * @param $adc
 * @param $supp
 * @return string
 */
function buildLaneWinPercentage($top, $jungle, $mid, $adc, $supp) {
    $html = "";
    $html .= '<div name="win_perc_by_lane_row" class="row">';
    $html .= '<div name="win_perc_by_lane" class="col-lg-3 add_gray_fill indent_me add_border">';
    $html .= '<h4 class="underline_me">Win % by Role:</h4>';
    $html .= '<p></p><b>Top Win %: '.$top.'</b><p></p>';
    $html .= '<b>Mid Win %: '.$mid.'</b><p></p>';
    $html .= '<b>Jungle Win %: '.$jungle.'</b><p></p>';
    $html .= '<b>ADC Win %: '.$adc.'</b><p></p>';
    $html .= '<b>Support Win %: '.$supp.'</b><p></p>';
    $html .= '</div><!-- END win_perc_by_lane -->';
    $html .= '</div> <!-- END win_perc_by_lane_row -->';
    return $html;
}

/**
 * @param $c1 - Champ One Name
 * @param $c1w - Champ One win percentage
 * @param $c2 - Champ Two Name
 * @param $c2w - Champ Two win percentage
 * @param $c3 ...
 * @param $c3w ...
 * @param $c4 ...
 * @param $c4w ...
 * @param $c5 ...
 * @param $c5w ...
 * @param string $c6  - OPTIONAL
 * @param int $c6w - OPTIONAL
 * @param string $c7 - OPTIONAL
 * @param int $c7w - OPTIONAL
 * @param string $c8 - OPTIONAL
 * @param int $c8w - OPTIONAL
 * @param string $c9 - OPTIONAL
 * @param int $c9w - OPTIONAL
 * @param string $c10 - OPTIONAL
 * @param int $c10w - OPTIONAL
 * @return string - HTML of div section
 */
function buildChampWinPercentage($arr) {
    $html = "";
    $html .= '<div name="win_perc_by_lane_row" class="row">';
        $html .= '<div name="win_perc_by_champs_1" class="col-lg-9 indent_me ">';
            $html .= '<h4 class="underline_me">Win % by Champs:</h4>';
            $html .= '<div class="recent_games_size">';
                $html .= '<table id="win_perc_by_lane_table" class="table table-striped table-hover">';
                $html .= '<thead class="header_bg">
            <th>Champion</th>
            <th>Games Won</th>
            <th>Total Games</th>
            <th> Win % </th></tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    for($i = 0; $i < sizeof($arr); $i++) {
        $html .= '<tr>';
        $html .= '<td >'.$arr[$i]->champ_name.'</td>';
        $html .= '<td >'.$arr[$i]->games_won.'</td>';
        $html .= '<td >'.$arr[$i]->total_games.'</td>';
        $html .= '<td >'.$arr[$i]->win_perc.'</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
    $html .= '</div>';
    $html .= '</div><!-- END win_perc_by_champs_1 -->';
    $html .= '</div> <!-- END win_perc_by_lane_row -->';
    /*$i = 0;
    while($i < 5) {

        $i++;
    }
    $html .= '</div><!-- END win_perc_by_champs_1 -->';
    if(sizeof($arr) > 5) {
        $html .= '  <div name="win_perc_by_champs_2" class="col-lg-3 add_gray_fill indent_me add_border">';
        $html .= '<h4> </h4><p></p>';
        while($i < 10) {
            $html .= '<b>'.$arr[$i]->champ_name.' Win %: '.$arr[$i]->win_perc.'</b><p></p>';
            $i++;
        }
        $html .= '</div><!-- END win_perc_by_champs_2 -->';
    }*/

    return $html;
}