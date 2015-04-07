<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/23/14
 * Time: 1:48 PM
 */

class Champion {

    var $summonerId = 0;
    var $participantId = 0;
    var $championId = 0;
    var $teamId = 0;
    var $name = "";
    var $cs = 0;
    var $kills = 0;
    var $assists = 0;
    var $deaths = 0;
    var $goldEarned = 0;
    var $goldSpent = 0;
    var $championLevel = 0;
    var $lane = "";
    var $role = "";
    var $firstBloodAssist = 0;
    var $firstBloodKill = 0;
    var $firstInhibitorKill = 0;
    var $firstInhibitorAssist = 0;
    var $firstTowerKill = 0;
    var $firstTowerAssist = 0;
    var $inhibitorKills = 0;
    var $killingSprees = 0;
    var $largestKillingSpree = 0;
    var $largestCriticalStrike = 0;
    var $towerKills = 0;
    var $doubleKills = 0;
    var $tripleKills = 0;
    var $quadraKills = 0;
    var $pentaKills = 0;
    var $unrealKills = 0;
    var $neutralMinionsKilled = 0;
    var $sightwardsBought = 0;
    var $visionwardsBought = 0;
    var $wardsKilled = 0;
    var $wardsPlaced = 0;
    var $spell1Id = 0;
    var $spell2Id = 0;
    var $highestAchievedSeasonTier = "";
    var $item0 = 0;
    var $item1 = 0;
    var $item2 = 0;
    var $item3 = 0;
    var $item4 = 0;
    var $item5 = 0;
    var $item6 = 0;
    var $totalDamageDealt = 0;
    var $totalDamageDealtToChampions = 0;
    var $totalDamageTaken = 0;
    var $totalHeal = 0;


    function __construct($teamId = 0, $participantId = 0, $championId = 0, $kills = 0, $assists = 0,
                        $deaths = 0, $goldearn = 0, $goldspent = 0,
                        $champLevel = 0, $firstBloodAssist = 0, $firstBloodKill = 0,
                        $firstInhibitorKill = 0, $firstInhibitorAssist = 0, $firstTowerKill = 0,
                        $firstTowerAssist = 0, $inhibitorKills = 0, $killingSprees = 0,
                        $largestKillingSpree = 0, $largestCriticalStrike = 0, $towerKills = 0, $doubleKills = 0,
                        $tripleKills = 0, $quadraKills = 0, $pentaKills = 0, $unrealKills = 0 ,
                        $highestAchSeasonTier = "", $spell1Id = 0, $spell2Id = 0, $item0 = 0,
                        $item1 = 0, $item2 = 0, $item3 = 0, $item4 = 0, $item5 = 0, $item6 = 0,
                        $spell1Id = 0, $spell2Id = 0, $highestAchSeasonTier = "",
                        $totalDamageDealt = 0, $totalDamageDealtToChampions = 0, $totalDamageTaken = 0,
                        $totalHeal = 0) {
        $this->teamId = $teamId;
        $this->participantId = $participantId;
        $this->championId = $championId;
        $this->kills = $kills;
        $this->deaths = $deaths;
        $this->assists = $assists;
        $this->goldEarned = $goldearn;
        $this->goldSpent = $goldspent;
        $this->championLevel = $champLevel;
        $this->firstBloodAssist = $firstBloodAssist;
        $this->firstBloodKill = $firstBloodKill;
        $this->firstInhibitorKill = $firstInhibitorKill;
        $this->firstInhibitorAssist = $firstInhibitorAssist;
        $this->firstTowerKill = $firstTowerKill;
        $this->firstTowerAssist = $firstTowerAssist;
        $this->inhibitorKills = $inhibitorKills;
        $this->killingSprees = $killingSprees;
        $this->largestKillingSpree = $largestKillingSpree;
        $this->largestCriticalStrike = $largestCriticalStrike;
        $this->towerKills = $towerKills;
        $this->doubleKills = $doubleKills;
        $this->tripleKills = $tripleKills;
        $this->quadraKills = $quadraKills;
        $this->pentaKills = $pentaKills;
        $this->unrealKills = $unrealKills;
        $this->spell1Id = $spell1Id;
        $this->spell2Id = $spell2Id;
        $this->highestAchievedSeasonTier = $highestAchSeasonTier;
        $this->item0 = $item0;
        $this->item1 = $item1;
        $this->item2 = $item2;
        $this->item3 = $item3;
        $this->item4 = $item4;
        $this->item5 = $item5;
        $this->item6 = $item6;
        $this->totalDamageDealt = $totalDamageDealt;
        $this->totalDamageDealtToChampions = $totalDamageDealtToChampions;
        $this->totalDamageTaken = $totalDamageTaken;
        $this->totalHeal = $totalHeal;
    }

    /*************** Setters ***************/
    function SetName($n) {
        $this->name = $n;
    }

    function SetKills($k) {
        $this->kills = $k;
    }

    function SetAssists($a) {
        $this->assists = $a;
    }

    function SetDeaths($d) {
        $this->deaths = $d;
    }

    function SetCS($cs) {
        $this->cs = $cs;
    }

    function SetGoldEarned($ge) {
        $this->goldEarned = $ge;
    }

    function SetGoldSpent($gs) {
        $this->goldSpent = $gs;
    }

    function SetChampLevel($n) {
        $this->championLevel = $n;
    }

    function SetFirstBloodAssist($k) {
        $this->firstBloodAssist = $k;
    }

    function SetFirstBloodKill($a) {
        $this->firstBloodKill = $a;
    }

    function SetFirstInhibitorKill($d) {
        $this->firstInhibitorKill = $d;
    }

    function SetFirstInhibitorAssist($cs) {
        $this->firstInhibitorAssist = $cs;
    }

    function SetFirstTowerKill($ge) {
        $this->firstTowerKill = $ge;
    }

    function SetFirstTowerAssist($gs) {
        $this->firstTowerAssist = $gs;
    }

    function SetInhibitorKills($n) {
        $this->inhibitorKills = $n;
    }

    function SetKillingSprees($k) {
        $this->killingSprees = $k;
    }

    function SetLargestKillingSpree($a) {
        $this->largestKillingSpree = $a;
    }

    function SetLargestCriticalStrike($a) {
        $this->largestCriticalStrike = $a;
    }

    function SetTowerKills($d) {
        $this->towerKills = $d;
    }

    function SetDoubleKills($cs) {
        $this->doubleKills = $cs;
    }

    function SetTripleKills($ge) {
        $this->tripleKills = $ge;
    }

    function SetQuadraKills($gs) {
        $this->quadraKills = $gs;
    }

    function SetPentaKills($n) {
        $this->pentaKills = $n;
    }

    function SetUnrealKills($n) {
        $this->unrealKills = $n;
    }

    function SetParticipantID($d) {
        $this->participantId = $d;
    }

    function SetChampionID($id) {
        $this->championId = $id;
    }

    function SetTeamID($t) {
        $this->teamId = $t;
    }

    function SetLane($gs) {
        $this->lane = $gs;
    }

    function SetRole($n) {
        $this->role = $n;
    }

    function SetSummonerID($id) {
        $this->summonerId = $id;
    }

    function SetSpell1ID($id) {
        $this->spell1Id = $id;
    }

    function SetSpell2ID($id) {
        $this->spell2Id = $id;
    }

    function SetSHighestAchSeasonTier($str) {
        $this->highestAchievedSeasonTier = $str;
    }

    function SetItem($itemNum, $itemId) {
        if($itemNum == 0){
            $this->item0 = $itemId;
        } else if($itemNum == 1){
            $this->item1 = $itemId;
        } else if($itemNum == 2){
            $this->item2 = $itemId;
        } else if($itemNum == 3){
            $this->item3 = $itemId;
        } else if($itemNum == 4){
            $this->item4 = $itemId;
        } else if($itemNum == 5){
            $this->item5 = $itemId;
        } else if($itemNum == 6){
            $this->item6 = $itemId;
        }
    }

    function SetTotalDamageDealt($num) {
        $this->totalDamageDealt = $num;
    }

    function SetTotalDamageDealtToChampions($num) {
        $this->totalDamageDealtToChampions = $num;
    }

    function SetTotalDamageTaken($num) {
        $this->totalDamageTaken = $num;
    }

    function SetTotalHeal($num) {
        $this->totalHeal = $num;
    }

    /*************** Getters *********************/


    function GetName() {
        return $this->name;
    }

    function GetKills() {
        return $this->kills;
    }

    function GetAssists() {
        return $this->assists;
    }

    function GetDeaths() {
        return $this->deaths;
    }

    function GetCS() {
        return $this->cs;
    }

    function GetGoldEarned() {
        return $this->goldEarned;
    }

    function GetGoldSpent() {
        return $this->goldSpent;
    }

    function GetChampLevel() {
        return $this->championLevel;
    }

    function GetFirstBloodAssist() {
        return $this->firstBloodAssist;
    }

    function GetFirstBloodKill() {
        return $this->firstBloodKill;
    }

    function GetFirstInhibitorKill() {
        return $this->firstInhibitorKill;
    }

    function GetFirstInhibitorAssist() {
        return $this->firstInhibitorAssist;
    }

    function GetFirstTowerKill() {
        return $this->firstTowerKill;
    }

    function GetFirstTowerAssist() {
        return $this->firstTowerAssist;
    }

    function GetInhibitorKills() {
        return $this->inhibitorKills;
    }

    function GetKillingSprees() {
        return$this->killingSprees;
    }

    function GetLargestKillingSpree() {
        return $this->largestKillingSpree;
    }

    function GetLargestCriticalStrike() {
        return $this->largestCriticalStrike;
    }

    function GetTowerKills() {
        return $this->towerKills;
    }

    function GetDoubleKills() {
        return $this->doubleKills;
    }

    function GetTripleKills() {
        return $this->tripleKills;
    }

    function GetQuadraKills() {
        return $this->quadraKills;
    }

    function GetPentaKills() {
        return $this->pentaKills;
    }

    function GetParticipantID() {
        return $this->participantId;
    }

    function GetChampionID() {
        return $this->championId;
    }

    function GetTeamID() {
        return $this->teamId;
    }

    function GetLane() {
        return $this->lane;
    }

    function GetRole() {
        return $this->role;
    }

    function GetSummonerID() {
        return $this->summonerId;
    }

    function GetSpell1ID() {
        return $this->spell1Id;
    }

    function GetSpell2ID() {
        return $this->spell2Id;
    }

    function GetHighestAchSeasonTier() {
        return $this->highestAchievedSeasonTier;
    }

    function GetItem($itemNum) {
        $itemIDToReturn = -1;
        if($itemNum == 0){
            $itemIDToReturn = $this->item0;
        } else if($itemNum == 1){
            $itemIDToReturn = $this->item1;
        } else if($itemNum == 2){
            $itemIDToReturn = $this->item2;
        } else if($itemNum == 3){
            $itemIDToReturn = $this->item3;
        } else if($itemNum == 4){
            $itemIDToReturn = $this->item4;
        } else if($itemNum == 5){
            $itemIDToReturn = $this->item5;
        } else if($itemNum == 6){
            $itemIDToReturn = $this->item6;
        }
        return $itemIDToReturn;
    }

    function GetUnrealKills() {
        return $this->unrealKills;
    }

    function GetTotalDamageDealt() {
        return $this->totalDamageDealt;
    }

    function GetTotalDamageDealtToChampions() {
        return $this->totalDamageDealtToChampions;
    }

    function GetTotalDamageTaken() {
        return $this->totalDamageTaken;
    }

    function GetTotalHeal() {
        return $this->totalHeal;
    }
}