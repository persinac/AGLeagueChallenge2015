<?php
/**
 * Created by PhpStorm.
 * User: apersinger
 * Date: 04/22/15
 * Time: 4:15 PM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LoL Stats</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
    <link href="dist/css/group_ext.css" rel="stylesheet">
    <link href="dist/css/league_custom.css" rel="stylesheet">
    <link href="dist/css/graph_NetworkOfSummoners.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->
</head>
<body >

<div class="container">
    <!--
    <div ng-controller="navigationController">
        <p dynamic="renderHtml(myHTML)"></p>
    </div>-->
    <!-- Static navbar  -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">League of Legends</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul id="main_nav" class="nav navbar-nav">
                    <li id="nav_home" class="active"><a href="#home">Home</a></li>
                    <li id="nav_profile" class="active"><a href="#prof">Profile</a></li>
                    <li id="nav_events" class="active"><a href="#even">Events</a></li>
                    <li id="nav_logout" class="active"><a href="#log">Logout</a></li>
                    <!--<li id="nav_add_players"><a href="#php">PHP Info</a></li>-->
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="values">
        </div>
    </div>
    <div class="row">
        <div id="level" class="col-lg-6">
            <h3>Your current level is: <span id="curr_level">0</span></h3>
        </div>
        <div id="Exp" class="col-lg-6">
            <h3>Your current experience is: <span id="curr_exp">0</span></h3>
        </div>
    </div>

    <div id="dialog-modal" title="" class="container">
        <div id="workoutcontent" class="workout-content"></div>
        <div id="workout-footer" class="workout-footer"></div>
        <p></p>
    </div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins)-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="http://d3js.org/d3.v3.js" charset="utf-8"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<!-- Required for team details dropdown in match details view -->
<script src="dist/jq_ui/js/jquery-1.10.2.js"></script>
<script src="dist/jq_ui/js/jquery-ui-1.10.4.custom.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/1.2.10/jquery.floatThead.js"></script>
<!--<script src="dist/js/lol_builds.js"></script>-->
<script src="dist/js/challenge_builds.js"></script>
<script src="dist/js/api_interface.js"></script>
<script src="dist/js/graphing_utility.js"></script>
<script src="dist/js/svg-pan-zoom.min.js"></script>
<script src="dist/js/utilities.js"></script>


<script>

    var total = 0;

    $.expr[':'].containsIgnoreCase = function (n, i, m) {
        /*console.log(n + "," + i  + "," + m);
         console.log("jQuery(n).text().toUpperCase(): "+jQuery(n).text().toUpperCase());
         console.log("jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()): "
         +jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()));*/
        return jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    $.expr[':'].doesNotContainIgnoreCase = function (n, i, m) {
        /*console.log(n + "," + i  + "," + m);
         console.log("jQuery(n).text().toUpperCase(): "+jQuery(n).text().toUpperCase());
         console.log("jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()): "
         +jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()));*/
        return jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase()) < 0;
    };

    $(document).ready(function() {
        loadHtml();
    });

    $("#main_nav").on("click", "li", function() {
        history.pushState(null, null, document.location.hash);
        var toParse = $(this).find('a').attr('href');
        $("ul").find("li.active").removeClass("active");
        $(this).addClass('active');
        console.log(toParse);
        if(toParse == "#basic") {
            buildBasicStats();
        } else if(toParse == "#adv") {
            alert("Maybe one day...");
        } else if(toParse == "#players") {
            buildPlayers();
        } else if(toParse == "#sbs") {
            buildAPIView();
        } else if(toParse == "#pac") {
            printAllChampions();
        } else if(toParse == "#cpr") {
            printChampionPriority();
        } else if(toParse == "#gar") {
            buildGarrettTestPage();
        } else if(toParse == "#gwp") {
            buildGroupPage();
        } /*else if(toParse == "#php") {
         phpInfo();
         }*/
    });

    function loadHtml() {
        html = "<h4>Strength:</h4>";

        html += '<form id="test_form">';
        html += 'Back Squat: <input type="text" name="back_squat" id="back_squat" value=""/><br>';
        html += 'Front Squat: <input type="text" name="front_squat" id="front_squat" value=""/><br>';
        html += 'Overhead Squat: <input type="text" name="overhead_squat" id="overhead_squat" value=""/><br>';
        html += 'Overhead Press: <input type="text" name="overhead_press" id="overhead_press" value=""/><br>';
        html += 'Push Press: <input type="text" name="push_press" id="push_press" value=""/><br>';
        html += 'Push Jerk: <input type="text" name="push_jerk" id="push_jerk" value=""/><br>';
        html += 'Power Clean: <input type="text" name="power_clean" id="power_clean" value=""/><br>';
        html += 'Power Snatch: <input type="text" name="power_snatch" id="power_snatch" value=""/><br>';
        html += 'Clean: <input type="text" name="clean" id="clean" value=""/><br>';
        html += 'Snatch: <input type="text" name="snatch" id="snatch" value=""/><br>';
        //html += "</form>";
        //html += '<a onclick="calculateExperience()" class="btn btn-primary btn-large" id="new_match_button" >Submit</a>';

        html += "<p></p><h4>Conditioning:</h4>";

        html += '1 mile run: ' + generateTimeDropDowns(1,1,1,'one_mi_run'); //<input type="text" name="one_mi_run" id="one_mi_run" value=""/><br>';
        html += '<br>2 mile run: ' + generateTimeDropDowns(1,1,1,'two_mi_run'); //<input type="text" name="two_mi_run" id="two_mi_run" value=""/><br>';
        html += '<br>1k row: ' + generateTimeDropDowns(1,1,1,'one_k_row'); //<input type="text" name="one_k_row" id="one_k_row" value=""/><br>';
        html += '<br>2k row: ' + generateTimeDropDowns(1,1,1,'two_k_row'); //<input type="text" name="two_k_row" id="two_k_row" value=""/><br>';
        html += '<br>5k row: ' + generateTimeDropDowns(1,1,1,'five_k_row'); //<input type="text" name="five_k_row" id="five_k_row" value=""/><br>';

        html += "<p></p><h4>Speed:</h4>";

        html += '100m Sprint: ' + generateTimeDropDowns(1,1,1,'100_m_spr'); //<input type="text" name="back_squat" id="back_squat" value=""/><br>';
        html += '<br>200m Sprint: ' + generateTimeDropDowns(1,1,1,'200_m_spr'); //<input type="text" name="front_squat" id="front_squat" value=""/><br>';
        html += '<br>400m: ' + generateTimeDropDowns(1,1,1,'400_m_spr'); //<input type="text" name="overhead_squat" id="overhead_squat" value=""/><br>';
        html += '<br>40yd Dash: ' + generateTimeDropDowns(1,1,1,'40_yd_dsh'); //<input type="text" name="overhead_press" id="overhead_press" value=""/><br>';
        /*
        html += "<p></p><h4>Skill:</h4>";

        //html += '<form id="test_con_form">';
        html += 'HSPU: <input type="text" name="back_squat" id="back_squat" value=""/><br>';
        html += 'Ring MU: <input type="text" name="front_squat" id="front_squat" value=""/><br>';
        html += 'Bar MU: <input type="text" name="overhead_squat" id="overhead_squat" value=""/><br>';
        html += 'Pistol: <input type="text" name="overhead_press" id="overhead_press" value=""/><br>';
        html += 'Double Under: <input type="text" name="push_press" id="push_press" value=""/><br>';
        html += 'Kipping Pull up: <input type="text" name="push_jerk" id="push_jerk" value=""/><br>';
        html += 'Butterfly PU: <input type="text" name="power_clean" id="power_clean" value=""/><br>';
        html += 'Hand Stand Walk: <input type="text" name="power_snatch" id="power_snatch" value=""/><br>';
 */
        html += "</form>";
        html += '<a onclick="calculateExperience()" class="btn btn-primary btn-large" id="new_match_button" >Submit</a>';

        $("#values").html(html);
    }

    function calculateExperience() {
        var data = $("#test_form").serializeArray();
        //var total = 0;
        var first_time = false;
        if(total < 1) {
            first_time = true;
        }
        $.each(data, function(i, field) {
            console.log("DATA: " + field.name + " : " + field.value);
            if(field.value.length > 0) {
                if(field.name.indexOf('rft_hr_selector_') > -1 ) {
                    total = total + ((parseInt(field.value) * 60 ) * 60 );
                } else if(field.name.indexOf('rft_min_selector_') > -1 ) {
                    total = total + (parseInt(field.value) * 60 );
                } else if(field.name.indexOf('rft_sec_selector_') > -1 ) {
                    total = total + parseInt(field.value);
                } else {
                    total = total + parseInt(field.value);
                }
                if (first_time == true) {
                    total = total + 50;
                }
            }
         });
        $("#curr_exp").html(total);
    }

</script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    /*TEST SERVER*/
    ga('create', 'UA-50665970-1', 'cboxbeta.com');

    /* LIVE SERVER */
    //ga('create', 'UA-50665970-2', 'compete-box.com');

    ga('send', 'pageview');

</script>

</body>
</html>