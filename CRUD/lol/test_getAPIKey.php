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
include('../../CRUD/library/LeagueAPIChallenge.php');

$lol = new Buckets('na', $my_key, $lol_host, $lol_un, $lol_pw, $lol_db);
$my_db_operations = new LeagueAPIChallenge('na', $lol_host, $lol_un, $lol_pw, $lol_db);
$date2 = date("Y-m-d H:i:s",strtotime(date("Y-m-d 08:00:00")));
$final_html .= '<p>Date: ' . $date2 .'</p>';
$val = strtotime($date2);
$final_html .= '<p>Date: ' . $val .'</p>';
$lol->SetBeginDate($val);
$matches = json_decode($lol->GetBucketOfMatches());

$final_html .= '<h3>Matches</h3>';
/**
 * 1. Get max bucketID from table
 */
$bucketId = $my_db_operations->GetMaxBucketId() + 1;
for($i = 0; $i < sizeof($matches); $i++) {
    $curr_matchId = $matches[$i];

     /**
      * 2. Insert new bucket based on current MAX bucketID
      */
    $retVal = $my_db_operations->InsertNewBucket($bucketId, $curr_matchId, 'na');
    $final_html .= '<p>************************** BEGIN MATCH *******************************</p>';
    $final_html .= '<p>INSERT BUCKETID RETVAL: ' . $retVal . '</p>';
    /**
     * 3. Insert new match based on matchID and current MAX bucketID
     */
    $match = new LeagueMatchDetails($matches[$i], 'na', $my_key, $lol_host, $lol_un, $lol_pw, $lol_db);
    $string = json_decode($match->GetMatchDetails());
    $final_html .= '<p>MatchID: ' . $string->matchId . '</p>';
    $final_html .= '<p>URL:' . $match->GetURL() . '</p>';
    $final_html .= '<p>TIME:' . $string->matchCreation . '</p>';
    /**
     * I don't really care about the milliseconds of the date, so I
     * divide the match creation by 1000
     */
    $final_html .= '<p>TIME:' . ($string->matchCreation / 1000) . '</p>';

    $retVal = $my_db_operations->InsertIntoMatchHeader($bucketId, $string->matchId
            , $string->mapId, $string->region, $string->platformId, $string->matchMode
            , $string->matchType, ($string->matchCreation / 1000), $string->matchDuration
            , $string->queueType, $string->season, $string->matchVersion);
    $final_html .= '<p>INSERT MATCHHEADER RETVAL: ' . $retVal . '</p>';
    $retVal = $my_db_operations->InsertIntoMatchDetails($curr_matchId, $match->GetFrameInterval($string));
    $final_html .= '<p>INSERT MATCHDETAIL RETVAL: ' . $retVal . '</p>';

    $test_champ = $match->ParticipantDetails($string);
    $team_details = $match->TeamDetails($string);
    $bans = $match->MatchBans($string, $curr_matchId);
    $final_html .= '<h4 style="text-indent: 50px;">Some Participant data: </h4>';
    $team_100 = '<p style="text-indent: 100px;">Team 100:</p>';
    $team_200 = '<p style="text-indent: 100px;">Team 200:</p>';

    $team_100_kills = 0;
    $team_100_assists = 0;
    $team_100_deaths = 0;
    $team_100_goldEarned = 0;
    $team_100_goldSpent = 0;

    $team_200_kills = 0;
    $team_200_assists = 0;
    $team_200_deaths = 0;
    $team_200_goldEarned = 0;
    $team_200_goldSpent = 0;
    $retVal = 0;
    foreach($test_champ AS $champ) {
        /**
         * 4. Insert participant details
         */
        $retVal += $my_db_operations->InsertIntoMatchParticipantDetails($curr_matchId, $champ->GetTeamID()
             , $champ->GetParticipantID(), $champ->GetSpell1ID(), $champ->GetSpell2ID(), $champ->GetChampionID()
             , $champ->GetHighestAchSeasonTier(), $champ->GetChampLevel(), $champ->GetItem(0)
             , $champ->GetItem(1), $champ->GetItem(2), $champ->GetItem(3), $champ->GetItem(4)
             , $champ->GetItem(5), $champ->GetItem(6), $champ->GetKills(), $champ->GetDoubleKills()
             , $champ->GetTripleKills(), $champ->GetQuadraKills(), $champ->GetPentaKills(), $champ->GetUnrealKills()
             , $champ->GetLargestKillingSpree(), $champ->GetDeaths(), $champ->GetAssists()
             , $champ->GetTotalDamageDealt(), $champ->GetTotalDamageDealtToChampions()
             , $champ->GetTotalDamageTaken(), $champ->GetLargestCriticalStrike(), $champ->GetTotalHeal()
             , $champ->GetGoldEarned(), $champ->GetGoldSpent(), $champ->GetRole(), $champ->GetLane());
        /**
         * 5. Insert participant details extended
         */
        $retVal += $my_db_operations->InsertIntoMatchParticipantDetails_Extended($curr_matchId, $champ->teamId,
            $champ->participantId,
            $champ->minionsKilled,
            $champ->neutralMinionsKilled,
            $champ->neutralMinionsKilledTeamJungle,
            $champ->neutralMinionsKilledEnemyJungle,
            $champ->combatPlayerScore,
            $champ->objectivePlayerScore,
            $champ->totalPlayerScore,
            $champ->totalScoreRank,
            $champ->magicDamageDealtToChampions,
            $champ->physicalDamageDealtToChampions,
            $champ->trueDamageDealtToChampions,
            $champ->visionWardsBoughtInGame,
            $champ->sightWardsBoughtInGame,
            $champ->magicDamageDealt,
            $champ->physicalDamageDealt,
            $champ->trueDamageDealt,
            $champ->magicDamageTaken,
            $champ->physicalDamageTaken,
            $champ->trueDamageTaken,
            $champ->firstBloodKill,
            $champ->firstBloodAssist,
            $champ->firstTowerKill,
            $champ->firstTowerAssist,
            $champ->firstInhibitorKill,
            $champ->firstInhibitorAssist,
            $champ->inhibitorKills,
            $champ->towerKills,
            $champ->wardsPlaced,
            $champ->wardsKilled,
            $champ->largestMultiKill,
            $champ->killingSprees,
            $champ->totalUnitsHealed,
            $champ->totalTimeCrowdControlDealt);
        if($champ->GetTeamID() == 100) {
            $team_100 .= '<p style="text-indent: 100px;">Champion ID: '.$champ->GetChampionID().' </p>';
            $team_100 .= '<p style="text-indent: 100px;">Total Damage Dealt: '.$champ->GetTotalDamageDealt().' </p>';
            $team_100 .= '<p style="text-indent: 100px;">Kills: '.$champ->GetKills().' </p>';
            $team_100_kills += $champ->GetKills();
            $team_100_assists += $champ->GetAssists();
            $team_100_deaths += $champ->GetDeaths();
            $team_100_goldEarned += $champ->GetGoldEarned();
            $team_100_goldSpent += $champ->GetGoldSpent();
        } else {
            $team_200 .= '<p style="text-indent: 100px;">Champion ID: '.$champ->GetChampionID().' </p>';
            $team_200 .= '<p style="text-indent: 100px;">Total Damage Dealt: '.$champ->GetTotalDamageDealt().' </p>';
            $team_200 .= '<p style="text-indent: 100px;">Kills: '.$champ->GetKills().' </p>';
            $team_200_kills += $champ->GetKills();
            $team_200_assists += $champ->GetAssists();
            $team_200_deaths += $champ->GetDeaths();
            $team_200_goldEarned += $champ->GetGoldEarned();
            $team_200_goldSpent += $champ->GetGoldSpent();
        }
    }
    $final_html .= '<p>INSERT MATCHPARTICIPANTDETAIL RETVAL: ' . $retVal . '</p>';
    $final_html .= $team_100 . $team_200;
    $final_html .= '<h4 style="text-indent: 50px;">Some Team data: </h4>';
    $team_detail_string = "";
    $retVal = 0;
    foreach($team_details AS $team) {
        /**
         * 6. Insert team details
         */
        if($team->teamId == 100) {
            $team->SetTotalKills($team_100_kills);
            $team->SetTotalAssists($team_100_assists);
            $team->SetTotalDeaths($team_100_deaths);
            $team->SetTotalGoldEarned($team_100_goldEarned);
            $team->SetTotalGoldSpent($team_100_goldSpent);
        } else {
            $team->SetTotalKills($team_200_kills);
            $team->SetTotalAssists($team_200_assists);
            $team->SetTotalDeaths($team_200_deaths);
            $team->SetTotalGoldEarned($team_200_goldEarned);
            $team->SetTotalGoldSpent($team_200_goldSpent);
        }
        $retVal += $my_db_operations->InsertIntoMatchTeamDetails($curr_matchId,
            $team->teamId,
            $team->baronKills,
            $team->dragonKills,
            $team->totalKills,
            $team->totalAssists,
            $team->totalDeaths,
            $team->totalGoldEarned,
            $team->totalGoldSpent,
            $team->winner,
            $team->firstBlood,
            $team->firstTower,
            $team->firstInhibitor,
            $team->firstBaron,
            $team->firstDragon,
            $team->vilemawKills);
        $final_html .= '<p style="text-indent: 100px;">Team ID: '.$team->teamId.' </p>';
        $final_html .= '<p style="text-indent: 100px;">Winner: '.$team->totalKills.' </p>';
        $final_html .= '<p style="text-indent: 100px;">First Tower: '.$team->firstTower.' </p>';
    }
    $final_html .= '<p>INSERT MATCHTEAMDETAIL RETVAL: ' . $retVal . '</p>';

    /**
     * 7. Insert into MatchBans
     */
    $retVal = 0;
    $retVal = $my_db_operations->InsertIntoMatchBans($bans->matchId, $bans->first, $bans->second,
        $bans->third, $bans->fourth, $bans->fifth, $bans->sixth);
    $final_html .= '<p>INSERT MATCHBANS RETVAL: ' . $retVal . '</p>';

    /*
     * 8. Get match events
     */
    $events = $match->MatchEvents($string);
    var_dump($events);
    $final_html .= '<p>************************** END MATCH *******************************</p>';
    $match->CloseConnection();
}

echo $final_html;
$my_db_operations->CloseConnection();
$lol->CloseConnection();