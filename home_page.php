<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 05/05/15
 * Time: 3:09 PM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CBox Leveler</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="dist/jq_ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
    <link href="dist/css/group_ext.css" rel="stylesheet">
    <link href="dist/css/league_custom.css" rel="stylesheet">
    <link href="dist/css/graph_NetworkOfSummoners.css" rel="stylesheet">
    <link href="dist/css/cbox_lvl_home_page.css" rel="stylesheet">
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
                <a class="navbar-brand" href="#">Leveler</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul id="main_nav" class="nav navbar-nav">
                    <li id="nav_home" class="active"><a href="#home">Home</a></li>
                    <li id="nav_profile" ><a href="#prof">Profile</a></li>
                    <li id="nav_events" ><a href="#even">Events</a></li>
                    <li id="nav_logout" ><a href="#log">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div id="first_row" class="row">
        <div id="left_col_row_1" class="col-lg-7">
            <div id="profile_pic_and_level" class="row">
                <img class="img-circle" src="holder.js/180x180" alt="Generic placeholder image">
                <div id="progressbar">
                    <div></div>
                </div>

            </div>
            <div id="ratings" class="row">
                <div class="col-lg-3">
                    <h4>Strength:</h4>
                    <h4>Conditioning:</h4>
                    <h4>Skill:</h4>
                    <h4>Speed:</h4>
                </div>
                <div class="col-lg-9">
                    <h4>50/100</h4>
                    <h4>35/100</h4>
                    <h4>11/100</h4>
                    <h4>23/100</h4>
                </div>

            </div>
        </div>
        <div id="left_col_row_1" class="col-lg-5">
            <div id="button_container" class="row">
                <a onclick="createNewChallenge()" class="btn btn-primary btn-large" id="new_match_button" >Create a Challenge</a><p></p>
                <a onclick="hostNewEvent()" class="btn btn-primary btn-large" id="new_match_button" >Host an Event</a><p></p>
                <a onclick="newPR()" class="btn btn-primary btn-large" id="new_match_button" >Destroy a PR</a><p></p>
                <a onclick="logWorkout()" class="btn btn-primary btn-large" id="new_match_button" >Log a workout</a><p></p>
            </div>
        </div>
    </div>

    <div id="second_row" class="row">
        <div id="left_col_row_2" class="col-lg-7">
            <div id="recent_activity" class="row">
                <h4>Recent Activity</h4>
                <p>User_X Logged a workout for the day!</p>
                <p>User_Y Placed 10th in her first scaled competition</p>
                <p>User_Z Beat his squat PR by 20lbs</p>
                <p>User_W reached level 50 and is now eligible for RX events!</p>
            </div>
        </div>
        <div id="rght_col_row_2" class="col-lg-5">
            <div id="upcoming_events" class="row">
                <h4>Upcoming Events</h4>
                <div id="events">
                    <div class="circle">13<span class="info">infoinfoinfo</span></div>
                    <div class="circle">10<span class="info">infoinfoinfo</span></div>
                    <div class="circle">15<span class="info">infoinfoinfo</span></div>
                </div>
            </div>
        </div>
    </div>

    <div id="third_row" class="row">
        <div id="left_col_row_3" class="col-lg-7">
            <div id="empty" class="row">
            </div>
        </div>
        <div id="rght_col_row_3" class="col-lg-5">
            <div id="challenges" class="row">
                <h4>Challenges</h4>
            </div>
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

<script src="holderJS/holder.js"></script>


<script>

    var total = 0;

    $(document).ready(function() {
        //loadHtml();
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

    $('#btn_click').on('click', function() { window.location = 'http://www.google.com'; });


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