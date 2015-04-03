<?php require_once('../../Connections/lol_conn.php'); ?>
<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/19/14
 * Time: 11:39 AM
 */


include('../../CRUD/library/league.php');

$all_champs = new league();
$all_champs->NewConnection($lol_host, $lol_un, $lol_pw, $lol_db);


$obj = $_POST['data'];
$cid = $obj["cid"];

$final_string = "";

$value = $obj["top"]["value"];
$changed = $obj["top"]["changed"];
$count = $obj["top"]["count"] + 1;
if($changed == 1) {
    $retVal = $all_champs->UpdateWorkingChampionLanePriority($cid, "t", $count, $value);
    if($retVal == 1) {
        //need to perform a select top_priority from workingChamp...
        $total = $all_champs->GetWorkingChampionLanePriority($cid, "t");
        $calcVal = $total / $count;
       // $final_string .=  "UPDATED TOP ChampionLanePriority with: ";
        //$final_string .=  "$total / $count, which = $calcVal\n";
        $retVal = $all_champs->UpdateChampionLanePriority($cid,"t",$calcVal);
        if($retVal == 1) {
            $final_string .= "Top lane priority update successfully!\n";
        } else {
            $final_string .= "Top lane priority update failed!\n";
        }
    } else {
        $final_string .= "Top lane priority update failed!\n";
    }
}

$value = $obj["jun"]["value"];
$changed = $obj["jun"]["changed"];
$count = $obj["jun"]["count"] + 1;
if($changed == 1) {
    $retVal = $all_champs->UpdateWorkingChampionLanePriority($cid, "j", $count, $value);
    if($retVal == 1) {
        $total = $all_champs->GetWorkingChampionLanePriority($cid, "j");
        $calcVal = $total / $count;
        $all_champs->UpdateChampionLanePriority($cid,"j",$calcVal);
        if($retVal == 1) {
            $final_string .= "Jungle lane priority update successfully!\n";
        } else {
            $final_string .= "Jungle lane priority update failed!\n";
        }
    } else {
        $final_string .= "Jungle lane priority update failed!\n";
    }
}


$value = $obj["mid"]["value"];
$changed = $obj["mid"]["changed"];
$count = $obj["mid"]["count"] + 1;
if($changed == 1) {
    $retVal = $all_champs->UpdateWorkingChampionLanePriority($cid, "m", $count, $value);
    if($retVal == 1) {
        $total = $all_champs->GetWorkingChampionLanePriority($cid, "m");
        $calcVal = $total / $count;
        $all_champs->UpdateChampionLanePriority($cid,"m",$calcVal);
        if($retVal == 1) {
            $final_string .= "Mid lane priority update successfully!\n";
        } else {
            $final_string .= "Mid lane priority update failed!\n";
        }
    } else {
        $final_string .= "Mid lane priority update failed!\n";
    }
}


$value = $obj["adc"]["value"];
$changed = $obj["adc"]["changed"];
$count = $obj["adc"]["count"] + 1;
if($changed == 1) {
    $retVal = $all_champs->UpdateWorkingChampionLanePriority($cid, "a", $count, $value);
    if($retVal == 1) {
        $total = $all_champs->GetWorkingChampionLanePriority($cid, "a");
        $calcVal = $total / $count;
        $all_champs->UpdateChampionLanePriority($cid,"a",$calcVal);
        if($retVal == 1) {
            $final_string .= "ADC lane priority update successfully!\n";
        } else {
            $final_string .= "ADC lane priority update failed!\n";
        }
    } else {
        $final_string .= "ADC lane priority update failed!\n";
    }

}

$value = $obj["sup"]["value"];
$changed = $obj["sup"]["changed"];
$count = $obj["sup"]["count"] + 1;
if($changed == 1) {
    $retVal = $all_champs->UpdateWorkingChampionLanePriority($cid, "s", $count, $value);
    if($retVal == 1) {
        $total = $all_champs->GetWorkingChampionLanePriority($cid, "s");
        $calcVal = $total / $count;
        $all_champs->UpdateChampionLanePriority($cid,"s",$calcVal);
        if($retVal == 1) {
            $final_string .= "Support lane priority update successfully!\n";
        } else {
            $final_string .= "Support lane priority update failed!\n";
        }
    } else {
        $final_string .= "Support lane priority update failed!\n";
    }
}
echo $final_string;
$all_champs->CloseConnection();