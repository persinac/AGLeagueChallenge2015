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
    var $kills = 0;
    var $assists = 0;
    var $deaths = 0;
    var $goldEarned = 0;
    var $goldSpent = 0;
    var $championLevel = 0;
    var $lane = "";
    var $role = "";
    var $largestKillingSpree = 0;
    var $largestCriticalStrike = 0;
    var $doubleKills = 0;
    var $tripleKills = 0;
    var $quadraKills = 0;
    var $pentaKills = 0;
    var $unrealKills = 0;
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
    /*Extended*/
    var $minionsKilled = 0;
    var $neutralMinionsKilled = 0;
    var $neutralMinionsKilledTeamJungle = 0;
    var $neutralMinionsKilledEnemyJungle = 0;
    var $combatPlayerScore = 0;
    var $objectivePlayerScore = 0;
    var $totalPlayerScore = 0;
    var $totalScoreRank = 0;
    var $magicDamageDealtToChampions = 0;
    var $physicalDamageDealtToChampions = 0;
    var $trueDamageDealtToChampions = 0;
    var $visionWardsBoughtInGame = 0;
    var $sightWardsBoughtInGame = 0;
    var $magicDamageDealt = 0;
    var $physicalDamageDealt = 0;
    var $trueDamageDealt = 0;
    var $magicDamageTaken = 0;
    var $physicalDamageTaken = 0;
    var $trueDamageTaken = 0;
    var $firstBloodAssist = 0;
    var $firstBloodKill = 0;
    var $firstInhibitorKill = 0;
    var $firstInhibitorAssist = 0;
    var $firstTowerKill = 0;
    var $firstTowerAssist = 0;
    var $inhibitorKills = 0;
    var $towerKills = 0;
    var $wardsKilled = 0;
    var $wardsPlaced = 0;
    var $killingSprees = 0;
    var $largestMultiKill = 0;
    var $totalUnitsHealed = 0;
    var $totalTimeCrowdControlDealt = 0;

    /**
     * Anything that is = 0 or = "" is in the extended table and is not required
     *
     * @param $teamId
     * @param $participantId
     * @param $championId
     * @param $kills
     * @param $assists
     * @param $deaths
     * @param $goldearn
     * @param $goldspent
     * @param $champLevel
     * @param int $firstBloodAssist
     * @param int $firstBloodKill
     * @param int $firstInhibitorKill
     * @param int $firstInhibitorAssist
     * @param int $firstTowerKill
     * @param int $firstTowerAssist
     * @param int $inhibitorKills
     * @param int $killingSprees
     * @param $largestKillingSpree
     * @param $largestCriticalStrike
     * @param int $towerKills
     * @param $doubleKills
     * @param $tripleKills
     * @param $quadraKills
     * @param $pentaKills
     * @param $unrealKills
     * @param $highestAchSeasonTier
     * @param $spell1Id
     * @param $spell2Id
     * @param $item0
     * @param $item1
     * @param $item2
     * @param $item3
     * @param $item4
     * @param $item5
     * @param $item6
     * @param $totalDamageDealt
     * @param $totalDamageDealtToChampions
     * @param $totalDamageTaken
     * @param $totalHeal
     * @param $role
     * @param $lane
     * @param int $minionsKilled
     * @param int $neutralMinionsKilled
     * @param int $neutralMinionsKilledTeamJungle
     * @param int $neutralMinionsKilledEnemyJungle
     * @param int $combatPlayerScore
     * @param int $objectivePlayerScore
     * @param int $totalPlayerScore
     * @param int $totalScoreRank
     * @param int $magicDamageDealtToChampions
     * @param int $physicalDamageDealtToChampions
     * @param int $trueDamageDealtToChampions
     * @param int $visionWardsBoughtInGame
     * @param int $sightWardsBoughtInGame
     * @param int $magicDamageDealt
     * @param int $physicalDamageDealt
     * @param int $trueDamageDealt
     * @param int $magicDamageTaken
     * @param int $physicalDamageTaken
     * @param int $trueDamageTaken
     * @param int $towerKills
     * @param int $wardsKilled
     * @param int $wardsPlaced
     * @param int $killingSprees
     * @param int $largestMultiKill
     * @param int $totalUnitsHealed
     * @param int $totalTimeCrowdControlDealt
     */
    function __construct($teamId, $participantId, $championId, $kills, $assists,
                        $deaths, $goldearn, $goldspent,
                        $champLevel, $firstBloodAssist = 0, $firstBloodKill = 0,
                        $firstInhibitorKill = 0, $firstInhibitorAssist = 0, $firstTowerKill = 0,
                        $firstTowerAssist = 0, $inhibitorKills = 0, $killingSprees = 0,
                        $largestKillingSpree, $largestCriticalStrike, $towerKills = 0, $doubleKills,
                        $tripleKills, $quadraKills, $pentaKills, $unrealKills,
                        $highestAchSeasonTier, $spell1Id, $spell2Id, $item0,
                        $item1, $item2, $item3, $item4, $item5, $item6,
                        $totalDamageDealt, $totalDamageDealtToChampions, $totalDamageTaken,
                        $totalHeal, $role, $lane, $minionsKilled = 0,
                        $neutralMinionsKilled = 0, $neutralMinionsKilledTeamJungle = 0,
                        $neutralMinionsKilledEnemyJungle = 0, $combatPlayerScore = 0,
                        $objectivePlayerScore = 0,  $totalPlayerScore = 0, $totalScoreRank = 0,
                        $magicDamageDealtToChampions = 0, $physicalDamageDealtToChampions = 0,
                        $trueDamageDealtToChampions = 0, $visionWardsBoughtInGame = 0, $sightWardsBoughtInGame = 0,
                        $magicDamageDealt = 0, $physicalDamageDealt = 0, $trueDamageDealt = 0, $magicDamageTaken = 0,
                        $physicalDamageTaken = 0, $trueDamageTaken = 0, $towerKills = 0, $wardsKilled = 0,
                        $wardsPlaced = 0, $killingSprees = 0, $largestMultiKill = 0, $totalUnitsHealed = 0,
                        $totalTimeCrowdControlDealt = 0) {
        $this->teamId = $teamId;
        $this->participantId = $participantId;
        $this->championId = $championId;
        $this->kills = $kills;
        $this->deaths = $deaths;
        $this->assists = $assists;
        $this->goldEarned = $goldearn;
        $this->goldSpent = $goldspent;
        $this->championLevel = $champLevel;
        $this->largestKillingSpree = $largestKillingSpree;
        $this->largestCriticalStrike = $largestCriticalStrike;
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
        $this->role = $role;
        $this->lane = $lane;
        /**
         * Extended
         */
        $this->minionsKilled = $minionsKilled;
        $this->neutralMinionsKilled = $neutralMinionsKilled;
        $this->neutralMinionsKilledTeamJungle = $neutralMinionsKilledTeamJungle;
        $this->neutralMinionsKilledEnemyJungle = $neutralMinionsKilledEnemyJungle;
        $this->combatPlayerScore = $combatPlayerScore;
        $this->objectivePlayerScore = $objectivePlayerScore;
        $this->totalPlayerScore = $totalPlayerScore;
        $this->totalScoreRank = $totalScoreRank;
        $this->magicDamageDealtToChampions = $magicDamageDealtToChampions;
        $this->physicalDamageDealtToChampions = $physicalDamageDealtToChampions;
        $this->trueDamageDealtToChampions = $trueDamageDealtToChampions;
        $this->visionWardsBoughtInGame = $visionWardsBoughtInGame;
        $this->sightWardsBoughtInGame = $sightWardsBoughtInGame;
        $this->magicDamageDealt = $magicDamageDealt;
        $this->physicalDamageDealt = $physicalDamageDealt;
        $this->trueDamageDealt = $trueDamageDealt;
        $this->magicDamageTaken = $magicDamageTaken;
        $this->physicalDamageTaken = $physicalDamageTaken;
        $this->trueDamageTaken = $trueDamageTaken;
        $this->firstBloodAssist = $firstBloodAssist;
        $this->firstBloodKill = $firstBloodKill;
        $this->firstInhibitorKill = $firstInhibitorKill;
        $this->firstInhibitorAssist = $firstInhibitorAssist;
        $this->firstTowerKill = $firstTowerKill;
        $this->firstTowerAssist = $firstTowerAssist;
        $this->inhibitorKills = $inhibitorKills;
        $this->towerKills = $towerKills;
        $this->wardsKilled = $wardsKilled;
        $this->wardsPlaced = $wardsPlaced;
        $this->killingSprees = $killingSprees;
        $this->largestMultiKill = $largestMultiKill;
        $this->totalUnitsHealed = $totalUnitsHealed;
        $this->totalTimeCrowdControlDealt = $totalTimeCrowdControlDealt;
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