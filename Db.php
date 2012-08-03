<?php

/**
* This file contains database information  
* Need of customized Survey prefix and title 
*        
* @author      Navdeep Bagga
* @authorEmail admin@navdeepbagga.com
* @category    database information
* @copyright   Copyright (c) June-July Testing and Consultancy Cell (http://www.navdeepbagga.com)
* @license     General Public License
* @version     $Id:Db.php 2012-01-08 $
*/

// Database information
$database    = 'limesurvey';
$dbHost      = 'localhost';
$dbUser      = 'dbusername';
$dbPass      = 'dbpassword';
$connection  = mysql_connect ($dbHost, $dbUser, $dbPass);
mysql_select_db ($database, $connection);

//Survey prefix and title	
$surveyTitle = 'Application Form for Faculty Position';
$prefixTable = 'lime_';
//Tables

$quesTable   = $prefixTable . 'questions';
$langTable   = $prefixTable . 'surveys_languagesettings';
$ansTable    = $prefixTable . 'answers';

?>
