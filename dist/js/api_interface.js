/**
 * Created by APersinger on 12/12/14.
 */

function buildAPIView() {
    //this will just be static...for now
    html = '<div name="api_test_row" class="row">';
        html += '<div name="api_test_1" class="col-lg-6">';
            html += '<b>Summoner Name:</b> <input type="text" name="summoner_to_find" id="summoner_to_find"/> ';
            html += '<a onclick="getSummonerDetails()" class="btn btn-primary btn-large" id="new_match_button" >Submit</a>';
        html += '</div>';
    html += '</div>';
    html += '<div name="api_recent_games_row" class="row">';
        html += '<div name="api_recent_games_div" id="api_recent_games_div" class="col-lg-12">';
        html += '</div>';
    html += '</div>'
    html += '<div name="api_test_match_row" class="row">';
        html += '<div name="api_test_match_details" id="api_test_match_details" class="col-lg-12">';
        html += '</div>';
    html += '</div>';

    $("#dyn_content").html(html);
}

function getSummonerDetails(opts) {
    if(typeof opts == "undefined") {
        summoner = $("#summoner_to_find").val();
    } else {
        summoner = opts;
        $("ul").find("li.active").removeClass("active");
        buildAPIView();
        $("#nav_search_by_sum").addClass('active');
    }

    $("#api_recent_games_div").html("<h4>Loading... please wait...</h4>");
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/getSummonerDetails.php",
        data: {"summoner":summoner},
        dataType: "html",
        success: function(response) {
            $("#api_recent_games_div").html(response);
            //insert match history
            backgroundInsertMatchHistory(true);
            //insert match header and match detail

        }
    });
}

function getSummonerWinPercs() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/api_getSummonerWinPercs.php",
        dataType: "html",
        success: function(response) {
            $("#win_perc_breakdown").html(response);
            var $table = $('#win_perc_by_lane_table');
            $table.floatThead({
                scrollContainer: function($table){
                    return $table.closest('.recent_games_size');
                }
            });
        }
    });
}

function api_matchDetails(gameid) {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/api_GetMatchDetails.php",
        data: {"gameid":gameid},
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#api_test_match_details").html(response);
        }
    });
}

function printAllChampions() {
    $.ajax({
        type: "GET",
        url: "/CRUD/lol/api_PrintAllChampions.php",
        dataType: "html",
        success: function(response) {
            //console.log(response);
            $("#dyn_content").html(response);
        }
    });
}
/*function phpInfo() {
    $.ajax({
        type: "POST",
        url: "/CRUD/general/getPHPInfo.php",
        dataType: "html",
        success: function(response) {
            $("#dyn_content").html(response);
        }
    });
}*/

function viewTeams(game_id) {
    console.log(game_id);

    $.ajax({
        type: "POST",
        url: "/CRUD/lol/api_GetTeamDetails.php",
        data: {"gameid":game_id},
        dataType: "html",
        success: function(response) {
            //console.log(response);
            var json = JSON.parse(response);
            str = json["html"].replace(/\\/g, '');
           // console.log( str);
            $("#dyn_content").html(str);
            parseTeamData(json['data']);
            $(function() {
                $("#accordion_match_details").accordion();
                $("#accordion_lane_details_row").accordion();
                //accordion_match_details
            });
            $( ".selector" ).accordion({
                header: "h2"
            });
            /*alert("If you're here, I'm collecting some data from this match, please don't hit the back" +
            "   button for at least 10 seconds (a real 10 seconds - not quick). Thanks!");*/


            //setTimeout(function(){ backgroundSummonerIDSearch(); }, 1000);

        }
    });
    /*$.each(jsonString, function(idx, obj) {
        console.log(obj.summonerId);
    });*/
    //notYetImplemented();
}

function notYetImplemented() {
    alert("This feature is not yet implemented");
}

function back() {
    closeTempFile();
    $.ajax({
        type: "GET",
        url: "/CRUD/general/api_goToPreviousPage.php",
        dataType: "html",
        success: function(response) {
            $("#dyn_content").html(response);
            getSummonerWinPercs();
        }
    });
}

function parseTeamData(data) {
    //console.log(data['team_totalKills']);
    graphTeamTotalKills(data['team_totalKills']);

}

function displaySummonerNetwork(data) {
    var html = "<div id=\"test_graph_stuff\"></div>";
    html += "<div id=\"mydata1\"></div>";
    console.log(html);
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/getSummonerNetwork.php",
        dataType: "html",
        success: function(response) {
            var json = JSON.parse(response);
            console.log(JSON.parse(json['graph_data']));
            $("#dyn_content").html(html);
            $("#mydata1").html(json['node_table_data']);
            $("#mydata1").append(json['link_table_data']);

            graph_FD_SummonerNetwork(json['graph_data']);
            panZoom = svgPanZoom('#mysvgele');
        },
        error: function (response) {
            console.log("ERROR: " + response);
        }
    });



}

function closeTempFile() {
    $.ajax({
        type: "POST",
        url: "/CRUD/general/closeOpenFile.php",
        dataType: "text",
        success: function(response) {
            console.log(response);
        }
    });
}

function backgroundSummonerIDSearch() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/api_backgroundSummonerIDSearch.php",
        dataType: "text",
        success: function(response) {
            console.log(response);
        }
    });
}

/**
 * Calls php file that inserts into matchhistory
 * @param comeFromGSD - Come from Get Summoner Details, if true, then populate the
 *      summoner's win percentages after inserting new data
 */
function backgroundInsertMatchHistory(comeFromGSD) {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/api_InsertMatchHistory.php",
        dataType: "text",
        success: function(response) {
            console.log(response);
            if(comeFromGSD == true) {
                getSummonerWinPercs();
            }
            backgroundViewTeams();

        }
    });
}

function backgroundViewTeams() {
    $.ajax({
        type: "POST",
        url: "/CRUD/lol/api_backgroundMatchInsert.php",
        dataType: "text",
        success: function(response) {
            console.log("backgroundViewTeams: "+response);
        }
    });
}
