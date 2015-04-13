/**
 * Created by APersinger on 04/12/15.
 */
/**
 * Created by APersinger on 12/03/14.
 */
function buildBasicStats() {
    response = "<h3>Coming soon!</h3><p>We've built the backend, we'll get it visualized shortly!</p>";
    $("#dyn_content").html(response);
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/challenge_getFrontPage.php",
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);

            var $table = $('#list_all_matches');
            $table.floatThead({
                scrollContainer: function($table){
                    console.log("What? "+$table.closest('.wrapper'));
                    return $table.closest('.main_match_table');
                }
            });
        }
    });
}


function printChampionPriority() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/printChampionPriority.php",
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
        }
    });
}

function updateLanePriority(cid) {

    //if($("#top_" + cid).val().match(/^[0-9]+$/) != null) {
    var tp = parseFloat($("#top_" + cid).val());
    var jp = parseFloat($("#jun_" + cid).val());
    var mp = parseFloat($("#mid_" + cid).val());
    var ap = parseFloat($("#adc_" + cid).val());
    var sp = parseFloat($("#sup_" + cid).val());
    var tp_count = parseInt($("#top_" + cid).attr("data-count"));
    var jp_count = parseInt($("#jun_" + cid).attr("data-count"));
    var mp_count = parseInt($("#mid_" + cid).attr("data-count"));
    var ap_count = parseInt($("#adc_" + cid).attr("data-count"));
    var sp_count = parseInt($("#sup_" + cid).attr("data-count"));
    var orig_top = parseFloat($("#top_" + cid).attr("data-orig"));
    var orig_jun = parseFloat($("#jun_" + cid).attr("data-orig"));
    var orig_mid = parseFloat($("#mid_" + cid).attr("data-orig"));
    var orig_adc = parseFloat($("#adc_" + cid).attr("data-orig"));
    var orig_sup = parseFloat($("#sup_" + cid).attr("data-orig"));

    console.log("INPUT: "+tp+","+jp+","+mp+","+ap+","+sp+"...");
    console.log("ORIG: "+orig_top+","+orig_jun+","+orig_mid+","+orig_adc+","+orig_sup+"...");

    var new_top = calculateAverage(orig_top, tp);
    var new_jun = calculateAverage(orig_jun, jp);
    var new_mid = calculateAverage(orig_mid, mp);
    var new_adc = calculateAverage(orig_adc, ap);
    var new_sup = calculateAverage(orig_sup, sp);

    console.log("value:"+new_top.retVal+" changed: "+new_top.changed+"");
    console.log("value:"+new_jun.retVal+" changed: "+new_jun.changed+"");
    console.log("value:"+new_mid.retVal+" changed: "+new_mid.changed+"");
    console.log("value:"+new_adc.retVal+" changed: "+new_adc.changed+"");
    console.log("value:"+new_sup.retVal+" changed: "+new_sup.changed+"");

    var dataToSend = { "cid":cid,
        "top" : {"value":new_top.retVal, "changed":new_top.changed, "count":tp_count},
        "jun" : {"value":new_jun.retVal, "changed":new_jun.changed, "count":jp_count},
        "mid" : {"value":new_mid.retVal, "changed":new_mid.changed, "count":mp_count},
        "adc" : {"value":new_adc.retVal, "changed":new_adc.changed, "count":ap_count},
        "sup" : {"value":new_sup.retVal, "changed":new_sup.changed, "count":sp_count}
    };

    $.ajax({
        type: "POST",
        url: "/CRUD/lol/updateChampionPriority.php",
        data: {"data":dataToSend},
        dataType: "html",
        success: function(response) {
            console.log(response);
            alert(response);
            printChampionPriority();
        }
    });
    // } else {
    //    alert("Numbers only please");
    // }

}

function matchDetails(matchid) {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/getMatchDetails.php",
        data: {"matchid":matchid},
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
        }
    });
}

function bucketDetails(matchid) {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/getMatchDetails.php",
        data: {"matchid":matchid},
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
        }
    });
}