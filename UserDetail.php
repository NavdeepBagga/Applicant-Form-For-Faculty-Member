<?php

/**
* This file create instance of class 
* Call functions of class
* Display Output
*        
* @author      Navdeep Bagga
* @authorEmail admin@navdeepbagga.com
* @category    Function call
* @copyright   Copyright (c) June-July Testing and Consultancy Cell
* @license     General Public License
* @version     $Id:UserDetail.php 2012-01-08 $
*/

//Include database and class file
require_once 'Db.php';
require_once 'QuesProcess.php';

//Create object of class	
$Ques_Process_Obj = new Ques_Processor();

//Define Array
$sampleArray = array(
    'gid', 'qid'
);
			
//Calling functions
$surveyId      = $Ques_Process_Obj -> createColumn('surveyls_survey_id', $langTable, 'surveyls_title', $surveyTitle);
$mainTable     = $prefixTable.'survey_'.$surveyId;
$emailId       = $Ques_Process_Obj -> createColumn($sampleArray,$quesTable,'question','Email');
$emailColm     = $surveyId."X".$emailId[0]."X".$emailId[1];
$questionsQid  = $Ques_Process_Obj -> createColumn('qid', $quesTable, 'parent_qid', 0);	
$quesInfo      = $Ques_Process_Obj -> getQuestionInfo($questionsQid, $quesTable);
$columnNames   = $Ques_Process_Obj -> questionColumnCreator($surveyId, $quesInfo, $quesTable);
$displayResult = $Ques_Process_Obj -> displayAnswer($columnNames, $mainTable, $emailColm);

//Display Answers
echo "<pre>";
print_r($columnNames);
print_r($displayResult);
echo "</pre>";	

?>
