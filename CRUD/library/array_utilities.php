<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/15/14
 * Time: 11:36 AM
 */

function dump_array($arr) {
    var_dump($arr);
}

function iterateArray($arr) {
    $build = '';
    foreach($arr AS $i=>$val) {
        if(is_array($val)) {
            echo "<b>$i:</b>";
            iterateArray($val, $build);
        } else if(is_object($val)) {
            echo "<b>$i:</b>";
            iterateArray($val, $build);
        } else {
            $build = "<p>$i: $val</p>";
            echo $build;
        }
    }
}

function my_sort($a, $b)
{
    echo "MY_SORT: $a->id > $b->id? = " . ($a->id > $b->id). " ";
    if ($a->id > $b->id) {
        return -1;
    } else if ($a->id < $b->id) {
        return 1;
    } else {
        return 0;
    }
}

function alphabetizeChampions($arr) {
    $x = array();
    foreach($arr AS $i=>$val) {
        $det = (object) array('name' => '', 'id' => '', 'title' => '');
        $det->name = $val->name;
        $det->id = $val->id;
        $det->title = $val->title;
        $x[] = $det;
        //$counter++;
    }
    usort($x, function($a, $b) { //Sort the array using a user defined function
        return $a->name < $b->name ? -1 : 1; //Compare the scores
    });
    return $x;
}

function convertTimeToDate($time) {
    $seconds = $time / 1000;
    return date("Y-m-d", $seconds);
}

function makeReadableTime($time) {
    $hours = floor($time / 3600);
    $mins = floor(($time - ($hours*3600)) / 60);
    $secs = floor($time % 60);
    $readabletime = '';
    if($hours == 0) {
        $readabletime = "00:";
    } else {
        $readabletime = $hours;
    }

    if($mins == 0) {
        $readabletime .= "00:";
    } else {
        $readabletime .= $mins . ":";
    }

    if($secs == 0) {
        $readabletime .= "00";
    } else {
        $readabletime .= $secs;
    }
    return $readabletime;
}

function mergeArrays($arr1, $arr2) {
    $ret_arr = array();
    $string = '';
    //$string .= "-_-_-_-_-_ MERGING _-_-_-_-_-_-";
    for($i = 0; $i < sizeof($arr1); $i++) {
        $obj = (object) array('sID'=>'','cID'=>'','tID'=>'','role'=>'','lane'=>'', 'found'=>'');
        for($j = 0; $j < sizeof($arr2); $j++) {

            if($arr1[$i]->cID == $arr2[$j]->cID && $arr1[$i]->tID == $arr2[$j]->tID) {
                /*$string .= "<p>I: $i, J: $j , ";
                $string .= "A1 SID: ".$arr1[$i]->sID .',';
                $string .= "A1 CID: ".$arr1[$i]->cID .',';
                $string .= "A1 TID: ".$arr1[$i]->tID .',';
                $string .= "A2 SID: ".$arr2[$j]->sID .',';
                $string .= "A2 CID: ".$arr2[$j]->cID .',';
                $string .= "A2 TID: ".$arr2[$j]->tID .'</p>';*/
                $obj->sID = $arr1[$i]->sID;
                $obj->cID = $arr1[$i]->cID;
                $obj->tID = $arr2[$j]->tID;
                $obj->role = $arr2[$j]->role;
                $obj->lane = $arr2[$j]->lane;
                $obj->found = 0;
                $ret_arr[] = $obj;
            }
        }
    }
    //echo $string;
    return $ret_arr;
}

function AddSummonerID($arr1, &$arr2) {
    $string = '';
    for($i = 0; $i < sizeof($arr1); $i++) {
        for ($j = 0; $j < sizeof($arr2); $j++) {
            if ($arr1[$i]->cID == $arr2[$j]->champID && $arr1[$i]->tID == $arr2[$j]->team) {
                $arr2[$j]->summonerID = $arr1[$i]->sID;
                break;
            }
        }
    }
}

function addPlayerToArray($arr, $sid, $tid, $cid) {
    $ret_arr = array();
    $string = '';
    for($i = 0; $i < sizeof($arr); $i++) {
        $obj = (object) array('sID'=>'','cID'=>'','tID'=>'');
        $obj->sID = $arr[$i]->summonerId;
        $obj->cID = $arr[$i]->championId;
        $obj->tID = $arr[$i]->teamId;
        $ret_arr[] = $obj;
    }
    $obj = (object) array('sID'=>'','cID'=>'','tID'=>'');
    $obj->sID = $sid;
    $obj->cID = $cid;
    $obj->tID = $tid;
    $ret_arr[] = $obj;
    //echo iterateArray($ret_arr);
    return $ret_arr;
}