<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/30/14
 * Time: 11:20 AM
 */

class Team {
    var $teamId = 0;
    var $baronKills = 0;
    var $dragonKills = 0;
    var $totalKills = 0;
    var $totalAssists = 0;
    var $totalDeaths = 0;
    var $totalGoldEarned = 0;
    var $totalGoldSpent = 0;
    var $winner = 0;
    var $firstBlood = 0;
    var $firstTower = 0;
    var $firstInhibitor = 0;
    var $firstBaron = 0;
    var $firstDragon = 0;
    var $towerKills = 0;
    var $vilemawKills = 0;

    function __construct($baronKills = 0, $dragonKills = 0, $fBaron = 0, $fBlood = 0,
                        $fDragon = 0, $fInhib = 0, $fTower = 0, $inhibKills = 0,
                        $teamId = 0, $towerKills = 0, $vilemawKills = 0, $winner = 0,
                        $totalKills, $totalAssists, $totalDeaths, $totalGoldEarned,
                        $totalGoldSpent) {

        $this->teamId = $teamId;
        $this->baronKills = $baronKills;
        $this->dragonKills = $dragonKills;
        $this->totalKills = $totalKills;
        $this->totalAssists = $totalAssists;
        $this->totalDeaths = $totalDeaths;
        $this->totalGoldEarned = $totalGoldEarned;
        $this->totalGoldSpent = $totalGoldSpent;
        $this->winner = $winner;
        $this->firstBlood = $fBlood;
        $this->firstTower = $fTower;
        $this->firstInhibitor = $fInhib;
        $this->firstBaron = $fBaron;
        $this->firstDragon = $fDragon;
        $this->towerKills = $towerKills;
        $this->vilemawKills = $vilemawKills;
    }

    function SetTotalKills($num) {
        $this->totalKills = $num;
    }

    function SetTotalAssists($num) {
        $this->totalAssists = $num;
    }

    function SetTotalDeaths($num) {
        $this->totalDeaths = $num;
    }

    function SetTotalGoldEarned($num) {
        $this->totalGoldEarned = $num;
    }

    function SetTotalGoldSpent($num) {
        $this->totalGoldSpent = $num;
    }
}
