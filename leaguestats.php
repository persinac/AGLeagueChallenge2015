<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/03/14
 * Time: 11:09 AM
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
                    <li id="nav_basic" class="active"><a href="#basic">Basics</a></li>
                    <li class="dropdown" style="z-index: 9999";>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Summoners<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li id="nav_players"><a href="#players">List All Summoners</a></li>
                            <li id="nav_search_by_sum"><a href="#sbs">Search for Summoner</a></li>
                            <li id="nav_group_win_perc"><a href="#gwp">Group Win %</a></li>
                        </ul>
                    </li>
                    <li class="dropdown" style="z-index: 9999";>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Test API<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li id="nav_prnt_all_champs"><a href="#pac">Print All Champions</a></li>
                            <li id="nav_champ_pri"><a href="#cpr">Champion Priority</a></li>
                        </ul>
                    </li>
                    <li id="nav_gar"><a href="#gar">Garrett Test Page</a></li>
                    <!--<li id="nav_add_players"><a href="#php">PHP Info</a></li>-->
                </ul>
            </div>
        </div>
    </div>

    <div id="dyn_content" class="">
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
<script src="dist/js/lol_builds.js"></script>
<script src="dist/js/api_interface.js"></script>
<script src="dist/js/graphing_utility.js"></script>
<script src="dist/js/svg-pan-zoom.min.js"></script>
<script src="dist/js/utilities.js"></script>


<script>

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
        var toParse = document.location.hash;
        var parsed = toParse.replace("#", "nav_");
        $("ul").find("li.active").removeClass("active");
        $("#"+parsed).addClass("active");
        if(toParse == "#basic") {
            buildBasicStats();
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
        } else {
            buildBasicStats();
        }
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

    function addPanZoom() {
       // var panZoom = svgPanZoom('#mysvgele');
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