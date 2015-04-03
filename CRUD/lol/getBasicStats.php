<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/03/14
 * Time: 11:27 AM
 */

session_start();

include('../../CRUD/library/league.php');

$lol = new league();

$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$lol->BuildAllMatchStats();
$det = $lol->GetAllMatches();
$final_html = '<div name="all_matches_row" class="row">';
//first table
$final_html .= '<div name="all_matches_div" class="col-lg-3">';
$final_html .= '<h3>All Matches</h3>';
$total_matches = $lol->GetNumberOfAllMatches();
$total_matches_won = $lol->GetNumberOfAllMatchesWon();
if($total_matches_won > 0) {
    $win_perc = number_format(100*($total_matches_won/$total_matches), 2, '.', '');
} else {
    $win_perc = number_format($total_matches_won, 2, '.', '');
}

$final_html .= '<h4>Win %:  '.$win_perc.'</h4>';
$final_html .= '</div>';
$final_html .= '<div name="all_matches_div" class="col-lg-9">';
$final_html .= '<div class="recent_games_size">';
$final_html .= '<table id="list_all_matches" class="table table-striped table-hover">';
$final_html .= '<thead class="header_bg">';
$final_html .= '<th>Match ID</th>';
$final_html .= '<th>First Game</th>';
$final_html .= '<th>Outcome</th>';
$final_html .= '<th>FF @ 20?</th>';
$final_html .= '<th>Date</th></tr>';
$final_html .= '</thead>';
$final_html .= '<tbody>';
foreach($det AS $key => $val) {
    $final_html .= '<tr>';
    $final_html .= '<td><a onclick="matchDetails('.$val->match_id.')">' . $val->match_id .'</a></td>';
    if($val->fg == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }

    if($val->outcome == 1) {
        $final_html .= '<td> <b>W</b> </td>';
    } else {
        $final_html .= '<td> <b>L</b> </td>';
    }

    if($val->ff == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }

    $final_html .= '<td>' . $val->date .'</td>';
    $final_html .= '</tr>';
}
$final_html .= '<tr></tr>';
$final_html .= '</tbody>';
$final_html .= '</table>';
$final_html .= '</div>';
$final_html .= '</div>'; //end all_matches_div
$final_html .= '</div>'; //end all_matches_row


$lol->BuildMatchStatsMoreSkins();
$det = $lol->GetMatchesMoreSkin();
$final_html .= '<div name="more_skin_matches_row" class="row">';
//first table
$final_html .= '<div name="more_skin_matches_div" class="col-lg-3">';
$final_html .= '<h3>Matches our team had more skins</h3>';
$total_matches = $lol->GetNumberOfMatchesMS();
$total_matches_won = $lol->GetNumberOfMatchesWonMoreSkin();
if($total_matches_won > 0) {
    $win_perc = number_format(100*($total_matches_won/$total_matches), 2, '.', '');
} else {
    $win_perc = number_format($total_matches_won, 2, '.', '');
}

$final_html .= '<h4>Win %:  '.$win_perc.'</h4>';
$final_html .= '</div>';
$final_html .= '<div name="more_skin_matches_div" class="col-lg-9">';
$final_html .= '<div class="recent_games_size">';
$final_html .= '<table id="list_more_skin_matches" class="table table-striped table-hover">';
$final_html .= '<thead class="header_bg">';
$final_html .= '<th>Match ID</th>';
$final_html .= '<th>First Game</th>';
$final_html .= '<th>Outcome</th>';
$final_html .= '<th>FF @ 20?</th>';
$final_html .= '<th>Date</th></tr>';
$final_html .= '</thead>';
$final_html .= '<tbody>';
foreach($det AS $key => $val) {
    $final_html .= '<tr>';
    $final_html .= '<td><a onclick="matchDetails('.$val->match_id.')">' . $val->match_id .'</a></td>';
    if($val->fg == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }
    if($val->outcome == 1) {
        $final_html .= '<td> <b>W</b> </td>';
    } else {
        $final_html .= '<td> <b>L</b> </td>';
    }

    if($val->ff == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }

    $final_html .= '<td>' . $val->date .'</td>';
    $final_html .= '</tr>';
}
$final_html .= '<tr></tr>';
$final_html .= '</tbody>';
$final_html .= '</table>';
$final_html .= '</div>';
$final_html .= '</div>'; //end all_matches_div
$final_html .= '</div>'; //end all_matches_row

$lol->BuildMatchStatsLessSkins();
$det = $lol->GetMatchesLessSkin();
$final_html .= '<div name="less_skin_matches_row" class="row">';
//first table
$final_html .= '<div name="less_skin_matches_div" class="col-lg-3">';
$final_html .= '<h3>Matches our team had less skins</h3>';
$total_matches = $lol->GetNumberOfMatchesLS();
$total_matches_won = $lol->GetNumberOfMatchesWonLessSkin();
if($total_matches_won > 0) {
    $win_perc = number_format(100*($total_matches_won/$total_matches), 2, '.', '');
} else {
    $win_perc = number_format($total_matches_won, 2, '.', '');
}

$final_html .= '<h4>Win %:  '.$win_perc.'</h4>';
$final_html .= '</div>';
$final_html .= '<div name="less_skin_matches_div" class="col-lg-9">';
$final_html .= '<div class="recent_games_size">';
$final_html .= '<table id="list_less_skin_matches" class="table table-striped table-hover">';
$final_html .= '<thead class="header_bg">';
$final_html .= '<th>Match ID</th>';
$final_html .= '<th>First Game</th>';
$final_html .= '<th>Outcome</th>';
$final_html .= '<th>FF @ 20?</th>';
$final_html .= '<th>Date</th></tr>';
$final_html .= '</thead>';
$final_html .= '<tbody>';
foreach($det AS $key => $val) {
    $final_html .= '<tr>';
    $final_html .= '<td><a onclick="matchDetails('.$val->match_id.')">' . $val->match_id .'</a></td>';

    if($val->fg == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }

    if($val->outcome == 1) {
        $final_html .= '<td> <b>W</b> </td>';
    } else {
        $final_html .= '<td> <b>L</b> </td>';
    }

    if($val->ff == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }

    $final_html .= '<td>' . $val->date .'</td>';
    $final_html .= '</tr>';
}
$final_html .= '<tr></tr>';
$final_html .= '</tbody>';
$final_html .= '</table>';
$final_html .= '</div>';
$final_html .= '</div>'; //end all_matches_div
$final_html .= '</div>'; //end all_matches_row

$lol->BuildMatchStatsEqualSkins();
$det = $lol->GetMatchesEqualSkin();
$final_html .= '<div name="equal_skin_matches_row" class="row">';
//first table
$final_html .= '<div name="equal_skin_matches_div" class="col-lg-3">';
$final_html .= '<h3>Matches both teams had equal skins</h3>';
$total_matches = $lol->GetNumberOfMatchesEQ();
$total_matches_won = $lol->GetNumberOfMatchesWonEqualSkin();
if($total_matches_won > 0) {
    $win_perc = number_format(100*($total_matches_won/$total_matches), 2, '.', '');
} else {
    $win_perc = number_format($total_matches_won, 2, '.', '');
}

$final_html .= '<h4>Win %:  '.$win_perc.'</h4>';
$final_html .= '</div>';
$final_html .= '<div name="equal_skin_matches_div" class="col-lg-9">';
$final_html .= '<div class="recent_games_size">';
$final_html .= '<table id="list_eq_skin_matches" class="table table-striped table-hover">';
$final_html .= '<thead class="header_bg">';
$final_html .= '<th>Match ID</th>';
$final_html .= '<th>First Game</th>';
$final_html .= '<th>Outcome</th>';
$final_html .= '<th>FF @ 20?</th>';
$final_html .= '<th>Date</th></tr>';
$final_html .= '</thead>';
$final_html .= '<tbody>';
foreach($det AS $key => $val) {
    $final_html .= '<tr>';
    $final_html .= '<td><a onclick="matchDetails('.$val->match_id.')">' . $val->match_id .'</a></td>';

    if($val->first_game == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }

    if($val->outcome == 1) {
        $final_html .= '<td> <b>W</b> </td>';
    } else {
        $final_html .= '<td> <b>L</b> </td>';
    }

    if($val->ff == 1) {
        $final_html .= '<td> <b>X</b> </td>';
    } else {
        $final_html .= '<td>  </td>';
    }

    $final_html .= '<td>' . $val->date .'</td>';
    $final_html .= '</tr>';
}
$final_html .= '<tr></tr>';
$final_html .= '</tbody>';
$final_html .= '</table>';
$final_html .= '</div>';
$final_html .= '</div>'; //end all_matches_div
$final_html .= '</div>'; //end all_matches_row


echo $final_html;
$lol->CloseConnection();