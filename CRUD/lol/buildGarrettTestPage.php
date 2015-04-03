<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/19/14
 * Time: 9:49 AM
 */

$champ = (object) array('champ_name'=>'','champ_id'=>'','top_priority'=>'',
        'jun_priority'=>'','mid_priority'=>'','adc_priority'=>'','supp_priority'=>'');

$html = "";

$html .= '<div class="row" id="notes_row">';
$html .= '<div class="col-lg-4" id="notes_div"><h3>Some notes...</h3><p>Along with parsing and reading JSON, you will ';
$html .= 'need to get familiar with OO PHP, ';
$html .= 'arrays of objects and objects that hold arrays that hold objects, and building the actual';
$html .= ' html in PHP. I will help you with styling the page, right now though, you just focus on understanding
  the API and how to manipulate the data.</div>';

$html .= '<div class="col-lg-8" id="notes_div">';
$html .= '<h3>Creating an object</h3>';
$html .= 'Going off our champ priority, I would build an object that resembles the data I am going to put into the DB,
    like so: </br>';
$html .= '$champ = (object) array(\'champ_name\'=>\'\', \'champ_id\'=>\'\', \'top_priority\'=>\'\', \'jun_priority\'=>\'\',
    \'mid_priority\'=>\'\', \'adc_priority\'=>\'\', \'supp_priority\'=>\'\');</br>';
$html .= 'That effectively creates the object. One of the easiest ways to see what\'s in the object is to use
    var_dump($champ). ';
$html .= 'Just note that using var_dump generally outputs to the top of the page - no matter where you place the
    statement.</br> ';
$html .= 'Our object is created, so let\'s assign it some values...
    <p><p>$champ->champ_name = "Varus";</p>
    <p>$champ->champ_id = 1234567890;</p>
    <p>$champ->top_priority = 5;</p>
    <p>$champ->jun_priority = 1;</p>
    <p>$champ->mid_priority = 6;</p>
    <p>$champ->adc_priority = 10;</p>
    <p>$champ->supp_priority = 3;</p></p>';

$champ->champ_name = "Varus";
$champ->champ_id = 1234567890;
$champ->top_priority = 5;
$champ->jun_priority = 1;
$champ->mid_priority = 6;
$champ->adc_priority = 10;
$champ->supp_priority = 3;

$html .= 'To access the values, just do it directly, like so:
    $html .= ID: $champ->champ_id Name: $champ->champ_name';
$html .= "<p>ID: $champ->champ_id Name: $champ->champ_name</p>";

$html .= 'Now, let\'s say we wanted to also include another champion. We\'d have to create an array to hold
    all these objects. Doing so is easy, $x = array();</br> To add objects to the array is even easier: $x[] = $champ;</br>
    <i>Note: you do have to create a new object every time, otherwise you overwrite the previous object</i></br></br>';

$x = array();
$x[] = $champ;
$champ = (object) array('champ_name'=>'','champ_id'=>'','top_priority'=>'',
    'jun_priority'=>'','mid_priority'=>'','adc_priority'=>'','supp_priority'=>'');
$champ->champ_name = "Twisted Fate";
$champ->champ_id = 213457899;
$champ->top_priority = 5;
$champ->jun_priority = 2;
$champ->mid_priority = 10;
$champ->adc_priority = 3;
$champ->supp_priority = 1;

$x[] = $champ;

$html .= 'To iterate through our array of objects, use either a for() or a foreach(). Depends on your mood/preference.';

$html .= '<p><b>FOREACH: </b>foreach(($x AS $i=>$val) {
    $html .= "<p>I: $i VAL->champ_name: $val->champ_name</p>";
}</p>';
foreach($x AS $i=>$val) {
    $html .= "<p>I: $i VAL->champ_name: $val->champ_name</p>";
}
$html .= '<p><b>FOR: </b>for($i = 0; $i < sizeof($x); $i++) {
    $html .= "<p>I: $i  X[i]->champ_name: " . $x[$i]->champ_name . "</p>";
}</p>';
for($i = 0; $i < sizeof($x); $i++) {
    $html .= "<p>I: $i  X[i]->champ_name: " . $x[$i]->champ_name . "</p>";
}

$html .= '';

$html .= '</div>';
$html .= "</div>";

echo $html;