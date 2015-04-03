<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 01/15/15
 * Time: 1:56 PM
 */

session_start();
include('../../CRUD/library/league.php');
include('../../CRUD/library/Combinations.php');
$t = json_encode($_POST['members']);
$members = json_decode($t);
//var_dump($members);
$lol = new league();
$lol->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);
$sql_string = "";
for($i = 0; $i < sizeof($members); $i++) {
    $sql_string .= ' AND (top = '.$members[$i]->value.'
                    OR mid = '.$members[$i]->value.'
                    OR jungle = '.$members[$i]->value.'
                    OR support = '.$members[$i]->value.'
                    OR adc = '.$members[$i]->value.') ';
}
$retVal = $lol->GetSummonerGroupsWinPerc($sql_string);

$games_won = 0;
$final_html = '<table id="list_of_games" class="table table-striped">';
$final_html .= '<thead><tr><th>Match ID</th>';
$final_html .= '<th>Outcome</th><th>Top</th>';
$final_html .= '<th>Jungle</th><th>Mid</th><th>Support</th><th>ADC</th></tr></thead>';
$final_html .= '<tbody>';
for($i = 0; $i < sizeof($retVal); $i++) {
    $outcome = $retVal[$i]->outcome;
    $final_html .= '<tr>';
    $final_html .= '<td>'.$retVal[$i]->matchid.'</td>';
    $final_html .= '<td>'.$outcome.'</td>';
    $final_html .= '<td>'.$retVal[$i]->top.'</td>';
    $final_html .= '<td>'.$retVal[$i]->jungle.'</td>';
    $final_html .= '<td>'.$retVal[$i]->mid.'</td>';
    $final_html .= '<td>'.$retVal[$i]->support.'</td>';
    $final_html .= '<td>'.$retVal[$i]->adc.'</td>';
    $final_html .= '</tr>';
    if($outcome == 1) {
        $games_won = $games_won + 1;
    }
}
$final_html .= '</tbody></table>';
$total_games = sizeof($retVal);
$win_perc = number_format( 100 * ( $games_won / $total_games ), 2, '.', '');
$stats_html = '<h4>Games Won: '.$games_won.'</h4>';
$stats_html .= '<h4>Total Games Played: '.$total_games.'</h4>';
$stats_html .= '<h4>Win Percentage: '.$win_perc.'</h4>';


$my_arr = array();
$x = $lol->GetMemberOfGroup(1);
$final2_html = '<h4>All Possible Team Combinations</h4>';
$final2_html .= '<table id="list_of_combos" class="table table-striped">';
$final2_html .= '<thead><tr><th> </th>';
$final2_html .= '<th> </th><th> </th>';
$final2_html .= '<th> </th><th> </th><th>Games Won</th><th>Games Played</th><th>Win %</th></tr></thead>';
$final2_html .= '<tbody>';
for($p = 1; $p < 6; $p++) {
    foreach (new Combinations($x, $p) as $substring) {
        $sql_string = "";
        $table_data = '';
        foreach ($substring AS $val) {
            $sql_string .= ' AND (top = ' . $val . '
                    OR mid = ' . $val . '
                    OR jungle = ' . $val . '
                    OR support = ' . $val . '
                    OR adc = ' . $val . ') ';
            $table_data .= '<td>' . $lol->GetSummonerName($val) . '</td>';
        }
        if($p < 5) {
            for($r = $p; $r < 5; $r++) {
                $table_data .= '<td> - </td>';
            }
        }
        $retVal = $lol->GetSummonerGroupsWinPerc($sql_string);
        $games_won = 0;
        for ($i = 0; $i < sizeof($retVal); $i++) {
            $outcome = $retVal[$i]->outcome;
            if ($outcome == 1) {
                $games_won = $games_won + 1;
            }
        }
        $total_games = sizeof($retVal);
        $win_perc = number_format(100 * ($games_won / $total_games), 2, '.', '');
        $table_data .= '<td>' . $games_won . '</td>';
        $table_data .= '<td>' . $total_games . '</td>';
        $table_data .= '<td>' . $win_perc . '</td>';
        if($win_perc > 79.99) {
            $final2_html .= '<tr class="groupWinperc">';
        } else {
            $final2_html .= '<tr>';
        }
        $final2_html .= $table_data;
        $final2_html .= '</tr>';
    }
}
$final2_html .= '</tbody></table>';
echo $stats_html . $final_html . $final2_html;
$lol->CloseConnection();

