<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/23/14
 * Time: 1:48 PM
 */

class Champion {

    var $summonerID = 0;
    var $participantID = 0;
    var $champID = 0;
    var $team = 0;
    var $name = "";
    var $cs = 0;
    var $kills = 0;
    var $assists = 0;
    var $deaths = 0;
    var $goldearned = 0;
    var $goldspent = 0;
    var $champLevel = 0;
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
    var $towerKills = 0;
    var $doubleKills = 0;
    var $tripleKills = 0;
    var $quadraKills = 0;
    var $pentaKills = 0;
    var $neutralMinionsKilled = 0;
    var $sightwardsBought = 0;
    var $visionwardsBought = 0;
    var $wardsKilled = 0;
    var $wardsPlaced = 0;




    function __construct($n = "", $cs = 0, $kills = 0, $assists = 0,
                        $deaths = 0, $goldearn = 0, $goldspent = 0,
                        $champLevel = 0, $firstBloodAssist = 0, $firstBloodKill = 0,
                        $firstInhibitorKill = 0, $firstInhibitorAssist = 0, $firstTowerKill = 0,
                        $firstTowerAssist = 0, $inhibitorKills = 0, $killingSprees = 0,
                        $largestKillingSpree = 0, $towerKills = 0, $doubleKills = 0,
                        $tripleKills = 0, $quadraKills = 0, $pentaKills = 0) {
        $this->name = $n;
        $this->cs = $cs;
        $this->kills = $kills;
        $this->deaths = $deaths;
        $this->assists = $assists;
        $this->goldearned = $goldearn;
        $this->goldspent = $goldspent;
        $this->champLevel = $champLevel;
        $this->firstBloodAssist = $firstBloodAssist;
        $this->firstBloodKill = $firstBloodKill;
        $this->firstInhibitorKill = $firstInhibitorKill;
        $this->firstInhibitorAssist = $firstInhibitorAssist;
        $this->firstTowerKill = $firstTowerKill;
        $this->firstTowerAssist = $firstTowerAssist;
        $this->inhibitorKills = $inhibitorKills;
        $this->killingSprees = $killingSprees;
        $this->largestKillingSpree = $largestKillingSpree;
        $this->towerKills = $towerKills;
        $this->doubleKills = $doubleKills;
        $this->tripleKills = $tripleKills;
        $this->quadraKills = $quadraKills;
        $this->pentaKills = $pentaKills;
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
        $this->goldearned = $ge;
    }

    function SetGoldSpent($gs) {
        $this->goldspent = $gs;
    }

    function SetChampLevel($n) {
        $this->champLevel = $n;
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

    function SetParticipantID($d) {
        $this->participantID = $d;
    }

    function SetChampionID($id) {
        $this->champID = $id;
    }

    function SetTeamID($t) {
        $this->team = $t;
    }

    function SetLane($gs) {
        $this->lane = $gs;
    }

    function SetRole($n) {
        $this->role = $n;
    }

    function SetSummonerID($id) {
        $this->summonerID = $id;
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
        return $this->goldearned;
    }

    function GetGoldSpent() {
        return $this->goldspent;
    }

    function GetChampLevel() {
        return $this->champLevel;
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
        return $this->participantID;
    }

    function GetChampionID() {
        return $this->champID;
    }

    function GetTeamID() {
        return $this->team;
    }

    function GetLane() {
        return $this->lane;
    }

    function GetRole() {
        return $this->role;
    }

    function GetSummonerID() {
        return $this->summonerID;
    }
}