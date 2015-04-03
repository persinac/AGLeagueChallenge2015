<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/30/14
 * Time: 11:20 AM
 */

class Team {
    var $baronKills = 0;
    var $dragonKills = 0;
    var $firstBaron = 0;
    var $firstBlood = 0;
    var $firstDragon = 0;
    var $firstInhibitor = 0;
    var $firstTower = 0;
    var $inhibitorKills = 0;
    var $teamId = 0;
    var $towerKills = 0;
    var $vilemawKills = 0;

    function __construct($bk = 0, $dk = 0, $fBaron = 0, $fBlood = 0,
                        $fDragon = 0, $fInhib = 0, $fTower = 0, $inhibKills = 0,
                        $teamId = 0, $tk = 0, $vk = 0) {

        $this->baronKills = $bk;
        $this->dragonKills = $dk;
        $this->firstBaron = $fBaron;
        $this->firstBlood = $fBlood;
        $this->firstDragon = $fDragon;
        $this->firstInhibitor = $fInhib;
        $this->firstTower = $fTower;
        $this->inhibitorKills = $inhibKills;
        $this->teamId = $teamId;
        $this->towerKills = $tk;
        $this->vilemawKills = $vk;
    }
}