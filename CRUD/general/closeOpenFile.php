<?php
/**
 * Created by PhpStorm.
 * User: APersinger
 * Date: 12/30/14
 * Time: 3:21 PM
 */
session_start();
//echo "SESSION: " . $_SESSION['tempFileName'];
unlink($_SESSION['tempFileName']);