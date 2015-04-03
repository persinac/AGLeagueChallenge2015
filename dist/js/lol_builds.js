/**
 * Created by APersinger on 12/03/14.
 */
function buildBasicStats() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/getBasicStats.php",
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
            var $table = $('#list_all_matches');
            $table.floatThead({
                scrollContainer: function($table){
                    console.log("What? "+$table.closest('.wrapper'));
                    return $table.closest('.recent_games_size');
                }
            });
            $table = $('#list_more_skin_matches');
            $table.floatThead({
                scrollContainer: function($table){
                    console.log("What? "+$table.closest('.wrapper'));
                    return $table.closest('.recent_games_size');
                }
            });
            $table = $('#list_less_skin_matches');
            $table.floatThead({
                scrollContainer: function($table){
                    console.log("What? "+$table.closest('.wrapper'));
                    return $table.closest('.recent_games_size');
                }
            });
            $table = $('#list_eq_skin_matches');
            $table.floatThead({
                scrollContainer: function($table){
                    console.log("What? "+$table.closest('.wrapper'));
                    return $table.closest('.recent_games_size');
                }
            });
        }
    });
}

function buildPlayers() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/getPlayers.php",
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
        }
    });
}

function buildAddNewMatch() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/buildAddNewMatch.php",
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
            $("#date").datepick({
                dateFormat: 'yyyy-mm-dd', alignment: 'bottom', changeMonth: false, autoSize: true
            });
            setUpChampAutoComplete();
        }
    });
}

function buildGarrettTestPage() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/buildGarrettTestPage.php",
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
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

function submitNewMatch() {
    var data = $("#new_match").serializeArray();
    /*$.each(data, function(i, field){
        console.log("DATA: " + field.name + " : " + field.value);
    });*/

    $.ajax({
        type: "POST",
        url: "/CRUD/lol/submitNewMatch.php",
        data: data,
        dataType: "text",
        success: function(response) {
            console.log(response);
            if(response == 1) {
                alert("Success!");
            } else {
                alert("Failed");
            }
        }
    });

}

function setUpChampAutoComplete() {
    $('#c_our_top_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        },
        onSelect: function (suggestion) {
            console.log('SELECTED: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_our_mid_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_our_jun_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_our_sup_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_our_adc_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_ene_top_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_ene_mid_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_ene_jun_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_ene_sup_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
    $('#c_ene_adc_input').autocomplete({
        source: '/CRUD/lol/championAutoComplete.php',
        success: function (suggestion) {
            //console.log('RESPONSE: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
}

function calculateAverage(original, input) {
    var new_val = 0;

    var json = '{"retVal":"", "changed":"0"}';
    var obj = JSON.parse(json);

    if(original == input) {
        obj.retVal = original;
        obj.changed = 0;
    } else {
        obj.retVal = input;
        obj.changed = 1;
    }
    return obj;
}

function summonerFinder() {
    var val = $("#summoner_search").val(); //.attr("data-name");
    console.log(val);
    if(val.length > 0) {
        console.log(val);
        var items = $("#all_players").find("tbody").find("tr:doesNotContainIgnoreCase('" + val + "')");
        if (items !== '' && typeof items !== 'undefined') {
            $.each(items, function(){
                /*console.log($(this).attr("style"));
                var t_val = $(this).attr("style");
                if(typeof t_val == "undefined") {
                    $(this).hide();
                } else {
                    $(this).show();
                }*/
                $(this).hide();
            });
        }
    } else {
        var items = $("#all_players").find("tbody").find("tr");
        if (items !== '' && typeof items !== 'undefined') {
            $.each(items, function(){
                $(this).show();
            });
        }
    }
    /*
     else if(val.length = 1) {
     console.log(val);
     var items = $("#all_players").find("tr:doesNotContainIgnoreCase('" + val + "')");
     if (items !== '' && typeof items !== 'undefined') {
     $.each(items, function(){
     console.log( this );
     $(this).show();
     });
     }
     }
     */
}

function buildGroupPage() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/buildGroupWinPercPage.php",
        dataType: "html",
        success: function(response) {
            $("#dyn_content").html(response);
        }
    });
}

$("#dyn_content").on("change", "#select_group", function() {
    console.log($(this).val());
    if( $(this).val() > 0 ) {
        $.ajax({
            type: "POST",
            url: "/CRUD/lol/getGroupMembers.php",
            data: {"groupid": $(this).val()},
            dataType: "html",
            success: function (response) {
                $("#group_mems_div").html(response);
            }
        });
    }
});

$("#dyn_content").on("click", ".summoner", function() {
    $("#group_mems_win_div").html("");
    var arr = new Array();
    var n = $( "input:checked" ).length;
    if(n > 0) {
        if( n < 6) {
            $("input:checked").each(function (i) {
                arr.push({"name": i, "value": $(this).val()})
            });
            $.ajax({
                type: "POST",
                url: "/CRUD/lol/getTheseMembersStats.php",
                data: {"members": arr},
                dataType: "html",
                success: function (response) {
                    $("#group_mems_win_div").html(response);
                }
            });
        } else {
            alert("Please only select up to 5 champions!");
        }
    }
});

function updateMatch() {
    var data = $("#new_match").serializeArray();

    $.ajax({
        type: "POST",
        url: "/CRUD/lol/updateMatch.php",
        data: data,
        dataType: "text",
        success: function(response) {
            console.log(response);
            if(response == 1) {
                alert("Success!");
            } else {
                alert("Failed");
            }
        }
    });

}
