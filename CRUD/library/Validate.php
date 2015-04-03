<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/19/14
 * Time: 9:00 AM
 */

class Validate extends riot_api {

    function __construct() {

    }
    /*
     * Iterate through team passed into function
     * Check that there is 1 top lane, 1 mid lane,
     *  1 jungle, and 2 bottom
     *
     * Valid values as per riot API:
     *  - TOP
     *  - MID
     *  - MIDDLE
     *  - JUNGLE
     *  - BOT
     *  - BOTTOM
     */
    function ValidateLanes(&$team) {
        $det = (object) array('allLanes'=>'','top'=>'','mid'=>'','jungle'=>'','bot'=>'');
        $det->allLanes = true;
        $det->top = array();
        $det->mid = array();
        $det->jungle = array();
        $det->bot = array();
        $t_count = 0;
        $m_count = 0;
        $j_count = 0;
        $b_count = 0;
        for($i = 0; $i < sizeof($team); $i++) {
            //echo "<p>CHAMPID: " . $team[$i]->champID . ", ".$team[$i]->lane.", ".$team[$i]->role."</p>";
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->cID = $team[$i]->cID;
            if($team[$i]->lane == "TOP") {
                $t_count++;
                $spec_lane->value = $t_count;
                $det->top[] = $spec_lane;
            } else if($team[$i]->lane == "JUNGLE") {
                $j_count++;
                $spec_lane->value = $j_count;
                $det->jungle[] = $spec_lane;
            }  else if($team[$i]->lane == "MID" || $team[$i]->lane == "MIDDLE") {
                $m_count++;
                $spec_lane->value = $m_count;
                $det->mid[] = $spec_lane;
            }  else if($team[$i]->lane == "BOT" || $team[$i]->lane == "BOTTOM") {
                $b_count++;
                $spec_lane->value = $b_count;
                $det->bot[] = $spec_lane;
            }
        }
        //echo "<p>COUNTS: top: $t_count, mid: $m_count, jun: $j_count, bot: $b_count</p>";
        if($t_count != 1) {
            $det->allLanes = false;
            $det->top->value = $t_count;
        }

        if($m_count != 1) {
            $det->allLanes =false;
            $det->mid->value = $m_count;
        }

        if($j_count != 1) {
            $det->allLanes =false;
            $det->jungle->value = $j_count;
        }

        if($b_count != 2) {
            $det->allLanes =false;
            $det->bot->value = $b_count;
        }

        return $det;
    }



    function FindInvalidLanes($lanes) {
        $top = $lanes->top;
        $mid = $lanes->mid;
        $jun = $lanes->jungle;
        $bot = $lanes->bot;

        $invalidLanes = array();
        //var_dump($lanes);
        if(sizeof($top) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "top";
            $spec_lane->cID = array();
            if(sizeof($top) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($top); $i++) {
                    $spec_lane->cID[] = $top[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        }

        if(sizeof($mid) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "mid";
            $spec_lane->cID = array();
            if(sizeof($mid) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($mid); $i++) {
                    $spec_lane->cID[] = $mid[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        }

        if(sizeof($jun) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "jun";
            $spec_lane->cID = array();
            if(sizeof($jun) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($jun); $i++) {
                    $spec_lane->cID[] = $jun[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        }

        if(sizeof($bot) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "bot";
            $spec_lane->cID = array();
            if(sizeof($bot) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($bot); $i++) {
                    $spec_lane->cID[] = $bot[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        }

        return $invalidLanes;
    }



    function CorrectInvalidLanes(&$team, $ilanes, $league_obj) {
        $new_lanes = array();
        $lane_priorities = array();
        $lane_to_check = '';
        $string = "";

        for($i = 0; $i < sizeof($ilanes); $i++) {
            $list_of_cid = $ilanes[$i]->cID;
            if($list_of_cid[0] > 0) {
                for ($j = 0; $j < sizeof($list_of_cid); $j++) {
                    $string .= "<p>Getting lane Priority for champ: " . $list_of_cid[$j];
                    $det = $league_obj->GetChampionLanePriorityByID($list_of_cid[$j]);
                    $string .= "<p>TOP Priority: " . $det->top . "</p>";
                    $string .= "<p>MID Priority: " . $det->mid . "</p>";
                    $string .= "<p>JUN Priority: " . $det->jun . "</p>";
                    $string .= "<p>BOT Priority: " . $det->adc . "</p>";
                    $string .= "<p>BOT Priority: " . $det->sup . "</p>";
                    $string .= "</p>";
                    $lane_priorities[] = $det;
                }
            } else {
                $lane_to_check = $ilanes[$i]->value;
            }
        }
        $lane_name = "";
        if($lane_to_check == 'top') {
            $lane_name = "TOP";
        } else if($lane_to_check == 'jun') {
            $lane_name = "JUNGLE";
        } else if($lane_to_check == 'mid') {
            $lane_name = "MIDDLE";
        } else {
            $lane_name = "BOTTOM";
        }
        $string .= "<p>LANE TO CHECK: $lane_to_check, LANE_NAME: $lane_name</p>";
        $max = -1;
        $indexToUse = -9;
        if(strlen($lane_to_check) > 1) {
            for ($i = 0; $i < sizeof($lane_priorities); $i++) {
                $string .= '<p>lane_priorities['.$i.']->'.$lane_to_check.': '. $lane_priorities[$i]->{$lane_to_check} . "</p> ";
                if ($lane_priorities[$i]->{$lane_to_check} > $max) {
                    $max = $lane_priorities[$i]->{$lane_to_check};
                    $indexToUse = $i;
                }
            }

            for ($i = 0; $i < sizeof($team); $i++) {
                if ($team[$i]->cID == $lane_priorities[$indexToUse]->cID) {
                    $team[$i]->lane = $lane_name;
                }
            }
        }
        //echo $string;
    }




    /****************** TESTING FUNCTIONS ***********************/

    function new_ValidateLanes(&$team, $league_obj) {
        $det = (object) array('allLanes'=>'','top'=>'','mid'=>'','jungle'=>'','adc'=>'', 'sup'=>'');
        $det->allLanes = true;
        $det->top = array();
        $det->mid = array();
        $det->jungle = array();
        $det->adc = array();
        $det->adc = array();
        $det->sup = array();
        $t_count = 0;
        $m_count = 0;
        $j_count = 0;
        $a_count = 0;
        $s_count = 0;
        $string = "";
        $champ_bot_1 = (object) array('cid'=>'','priority'=>'-1','role'=>'','team_index'=>'');
        $champ_bot_2 = (object) array('cid'=>'','priority'=>'-1','role'=>'','team_index'=>'');

        $string .= "<p>************NEW_VALIDATE LANES************</p>";
        for($i = 0; $i < sizeof($team); $i++) {
           $string .= "<p>CHAMPID: " . $team[$i]->champID . ", ".$team[$i]->lane.", ".$team[$i]->role."</p>";
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->cID = $team[$i]->champID;
            if($team[$i]->lane == "TOP") {
                $t_count++;
                $spec_lane->value = $t_count;
                $team[$i]->role = "SOLO";
                $det->top[] = $spec_lane;
            } else if($team[$i]->lane == "JUNGLE") {
                $j_count++;
                $spec_lane->value = $j_count;
                $team[$i]->role = "NONE";
                $det->jungle[] = $spec_lane;
            }  else if($team[$i]->lane == "MID" || $team[$i]->lane == "MIDDLE") {
                $m_count++;
                $spec_lane->value = $m_count;
                $team[$i]->role = "SOLO";
                $det->mid[] = $spec_lane;
            }  else if($team[$i]->lane == "BOT" || $team[$i]->lane == "BOTTOM") {
                if($champ_bot_1->priority == -1) {
                    $champ_bot_1->priority = $this->getADCPriority($spec_lane->cID, $league_obj);
                    $champ_bot_1->cid = $spec_lane->cID;
                    $champ_bot_1->team_index = $i;
                } else {
                    $a_count++;
                    $s_count++;
                    $champ_bot_2->priority = $this->getADCPriority($spec_lane->cID, $league_obj);
                    $champ_bot_2->cid = $spec_lane->cID;
                    $champ_bot_2->team_index = $i;
                    if($champ_bot_1->priority >= $champ_bot_2->priority) {
                        $spec_lane->value = $a_count;
                        $spec_lane->cID = $champ_bot_1->cid;
                        $champ_bot_1->role = "DUO_CARRY";
                        $det->adc[] = $spec_lane;
                        $spec_lane = (object) array('value'=>'','cID'=>'');
                        $spec_lane->value = $s_count;
                        $spec_lane->cID = $champ_bot_2->cid;
                        $champ_bot_2->role = "DUO_SUPPORT";
                        $det->sup[] = $spec_lane;
                        $team[$champ_bot_1->team_index]->role = "DUO_CARRY";
                        $team[$champ_bot_2->team_index]->role = "DUO_SUPPORT";
                    } else {
                        $spec_lane->value = $a_count;
                        $spec_lane->cID = $champ_bot_1->cid;
                        $champ_bot_1->role = "DUO_SUPPORT";
                        $det->sup[] = $spec_lane;
                        $spec_lane = (object) array('value'=>'','cID'=>'');
                        $spec_lane->value = $s_count;
                        $spec_lane->cID = $champ_bot_2->cid;
                        $champ_bot_2->role = "DUO_CARRY";
                        $det->adc[] = $spec_lane;
                        $team[$champ_bot_2->team_index]->role = "DUO_CARRY";
                        $team[$champ_bot_1->team_index]->role = "DUO_SUPPORT";
                    }
                }
            }
        }
        $string .= "<p>COUNTS: top: $t_count, mid: $m_count, jun: $j_count, adc: $a_count, sup: $s_count</p>";
        if($t_count != 1) {
            $det->allLanes = false;
            $det->top->value = $t_count;
        }

        if($m_count != 1) {
            $det->allLanes =false;
            $det->mid->value = $m_count;
        }

        if($j_count != 1) {
            $det->allLanes =false;
            $det->jungle->value = $j_count;
        }

        if($a_count != 1) {
            $det->allLanes =false;
            $det->adc->value = $a_count;
        }

        if($s_count != 1) {
            $det->allLanes =false;
            $det->sup->value = $s_count;
        }
        //echo $string;
        return $det;
    }


    function new_FindInvalidLanes($lanes) {
        $top = $lanes->top;
        $mid = $lanes->mid;
        $jun = $lanes->jungle;
        $adc = $lanes->adc;
        $sup = $lanes->sup;
        $invalidLanes = array();
        $string = "<p>************NEW_FIND INVALID LANES************</p>";
        if(sizeof($top) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "top";
            $spec_lane->cID = array();
            if(sizeof($top) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($top); $i++) {
                    $string .= "<p>TOP CHAMPID: " . $top[$i]->cID . "</p>";
                    $spec_lane->cID[] = $top[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        } else {
            $string .= "<p>TOP IS GOOD " .$top[0]->cID. "</p>";
        }

        if(sizeof($mid) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "mid";
            $spec_lane->cID = array();
            if(sizeof($mid) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($mid); $i++) {
                    $string .= "<p>MID CHAMPID: " . $mid[$i]->cID . "</p>";
                    $spec_lane->cID[] = $mid[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        } else {
            $string .= "<p>MID IS GOOD " .$mid[0]->cID. "</p>";
        }

        if(sizeof($jun) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "jun";
            $spec_lane->cID = array();
            if(sizeof($jun) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($jun); $i++) {
                    $string .= "<p>JUNGLE CHAMPID: " . $jun[$i]->cID . "</p>";
                    $spec_lane->cID[] = $jun[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        } else {
            $string .= "<p>JUNGLE IS GOOD " .$jun[0]->cID. "</p>";
        }

        if(sizeof($adc) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "adc";
            $spec_lane->cID = array();
            if(sizeof($adc) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($adc); $i++) {
                    $spec_lane->cID[] = $adc[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        }

        if(sizeof($sup) != 1) {
            $spec_lane = (object) array('value'=>'','cID'=>'');
            $spec_lane->value = "sup";
            $spec_lane->cID = array();
            if(sizeof($sup) == 0) {
                $spec_lane->cID[] = -1;
            } else {
                for($i = 0; $i < sizeof($sup); $i++) {
                    $spec_lane->cID[] = $sup[$i]->cID;
                }
            }
            $invalidLanes[] = $spec_lane;
        }


        //echo $string;

        return $invalidLanes;
    }

    function new_CorrectInvalidLanes(&$team, $ilanes, $league_obj) {
        $new_lanes = array();
        $lane_priorities = array();
        $lane_to_check = '';

        $string = "<p>************NEW_CorrectInvalidLanes************</p>";
        //var_dump($ilanes);
        for($i = 0; $i < sizeof($ilanes); $i++) {
            $list_of_cid = $ilanes[$i]->cID;
            $string .= "<p>".$ilanes[$i]->cID . ", " . $ilanes[$i]->value . "</p>";
            if ($list_of_cid[0] > 0) {
                for ($j = 0; $j < sizeof($list_of_cid); $j++) {
                    if ($list_of_cid[$j] > 0) {
                        $string .= "<p>Getting lane Priority for champ: " . $list_of_cid[$j];
                        $det = $league_obj->GetChampionLanePriorityByID($list_of_cid[$j]);
                        $string .= "<p>TOP Priority: " . $det->top . "</p>";
                        $string .= "<p>MID Priority: " . $det->mid . "</p>";
                        $string .= "<p>JUN Priority: " . $det->jun . "</p>";
                        $string .= "<p>ADC Priority: " . $det->adc . "</p>";
                        $string .= "<p>SUP Priority: " . $det->sup . "</p>";
                        $string .= "</p>";
                        $lane_priorities[] = $det;
                    }
                }
            } else {
                $lane_to_check = $ilanes[$i]->value;
            }
        }

        $lane_name = "";
        if($lane_to_check == 'top') {
            $lane_name = "TOP";
        } else if($lane_to_check == 'jun') {
            $lane_name = "JUNGLE";
        } else if($lane_to_check == 'mid') {
            $lane_name = "MIDDLE";
        } else {
            $lane_name = "BOTTOM";
        }
        $string .= "<p>LANE TO CHECK: $lane_to_check, LANE_NAME: $lane_name</p>";
        $max = -1;
        $indexToUse = -9;
        if(strlen($lane_to_check) > 1) {
            for ($i = 0; $i < sizeof($lane_priorities); $i++) {
                $string .= '<p>lane_priorities[$i]->{$lane_to_check}: '. $lane_priorities[$i]->{$lane_to_check} . "</p> ";
                if ($lane_priorities[$i]->{$lane_to_check} > $max) {
                    $max = $lane_priorities[$i]->{$lane_to_check};
                    $indexToUse = $i;
                }
            }

            for ($i = 0; $i < sizeof($team); $i++) {
                if ($team[$i]->champID == $lane_priorities[$indexToUse]->cID) {
                    $team[$i]->lane = $lane_name;
                }
            }
        }
        //echo $string;
    }

    function ValidateRoles($team) {

    }

    function getADCPriority($cID, $league_obj) {
        $prio = -1;
        $det = $league_obj->GetChampionLanePriorityByID($cID);
        $prio = $det->adc;
        return $prio;
    }

    function getSupPriority($cID, $league_obj) {
        $prio = -1;
        $det = $league_obj->GetChampionLanePriorityByID($cID);
        $prio = $det->sup;
        return $prio;
    }

    function BotCSValidation() {

    }

    function SwitchRoles() {

    }
}