<?php

/**
* Retrieve information from survey of Applicant Form for Faculty Member
*        
* @author      Navdeep Bagga
* @authorEmail admin@navdeepbagga.com
* @category    Retrieve Information
* @copyright   Copyright (c) June-July Testing and Consultancy Cell
* @license     General Public License
* @version     $Id:QuesProcess.php 2012-01-08 $
*/
require_once 'Db.php';
require_once 'UserDetail.php';

/**
* Get all information of questions form question table 
* Concatenate all retrieve Information and make column names
* Retrieve answers from column names of given email value from answer table     
*
* @category   Retrieve Information
* @copyright  Copyright (c) June-July Testing and Consultancy Cell
* @license    General Public License
* @version    Release: @package_version@
* @since      Class available since Release 1
* @deprecated Class deprecated in Release 2
*/
class Ques_Processor
{	
	/**
	 * This function retrieves specific column value from question table
	 * 
	 * @arguments column_name,question_table,where_column,selected_value
	 * 
	 * @return    columnvalue_of_question
     */		
    function createColumn($select, $table, $column, $title)
    {
        if (is_array($select))
        {
            foreach($select as $select1)
            {
                $selectArray = mysql_fetch_array(mysql_query("SELECT `$select1` from `$table` "
                             . "WHERE `$column` = '$title'"));
                $fetchArray[] = $selectArray[$select1];
            }
            return $fetchArray;
        }
        $selectCol = mysql_fetch_array(mysql_query("SELECT `$select` from `$table` " 
                   . "WHERE `$column` = '$title' "
                   . "ORDER BY `$select` DESC LIMIT 1;"));
        $fetchCol = $selectCol[$select];	
        return $fetchCol;
    }	
	
    /**
     * This function retrieves all information like question id, type
     * and group id  of given question
     * 
     * @arguments last_question_id, question_table
     * 
     * @return    array of gid,qid,type of question
     */		
    function getQuestionInfo($lastQid, $table)
    {
        for($ques = 1; $ques <= $lastQid; $ques++)
        {
            $questionQid[] = $ques;
        }
        $counterA = 0;
        foreach($questionQid as $singleQid) 
        {
            $selectQuesInfo = mysql_query("SELECT `qid`, `gid`, `type`,"
                            . "`other` from `$table` "
                            . "WHERE `qid` = '$singleQid'");
            $fetchQuesInfo = mysql_fetch_array($selectQuesInfo);
            $infoQues[$counterA][qid] = $fetchQuesInfo['qid'];	
            $infoQues[$counterA][gid] = $fetchQuesInfo['gid'];		
            $infoQues[$counterA][type] = $fetchQuesInfo['type'];	
            $infoQues[$counterA][other] = $fetchQuesInfo['other'];		
        $counterA++;
        }
        return $infoQues;	
    }

    /**
     * This function check the type of question, according to that make column names
     * 
     * @arguments survey id, question information, question table
     * 
     * @return    array_of_columnnames_of_question
     */	
    function questionColumnCreator($constSid, $quesInfo, $table)
    {			
        foreach($quesInfo as $singleInfo)
        {	       
            if (($singleInfo['type'] == ";")
                || ($singleInfo['type'] == ":")
            ) {	
                $selectUnderColm = mysql_query("SELECT `title`, `scale_id` from `$table` "
                                      . "WHERE `parent_qid` = '$singleInfo[qid]' " 
                                      . "AND `scale_id` = '0'");
                while($fetchUnderColm = mysql_fetch_array($selectUnderColm))	
                {
                    $selectRepeatColm = mysql_query("SELECT `title` from `$table` "
                                      . "WHERE `parent_qid` = '$singleInfo[qid]' "
                                      . "AND `scale_id` = '1'");
                    while($fetchRepeatColm = mysql_fetch_array($selectRepeatColm))
                    {
                        $compoundValue[] = $constSid."X".$singleInfo['gid']."X"
                                         . $singleInfo['qid'].$fetchUnderColm['title']
                                         . "_".$fetchRepeatColm['title'];
                    }
                }
              }
            elseif (($singleInfo['type'] == "M")
                    || ($singleInfo['type'] == "Q")
            ) {	
                $selectSqColm = mysql_query("SELECT `title` from `$table` "
                              . "WHERE `parent_qid` = '$singleInfo[qid]'");
                while($fetchSqColm = mysql_fetch_array($selectSqColm))	
                {
                    $compoundValue[] = $constSid."X".$singleInfo['gid']."X"
                                     . $singleInfo['qid'].$fetchSqColm['title'];
                }
                if ($singleInfo['other'] == "Y")
                {
                    $compoundValue[] = $constSid."X".$singleInfo['gid']
                                     . "X".$singleInfo['qid']."other";
                }
              }
           elseif ($singleInfo['type'] == "|")
           {
                $compoundValue[] = $constSid."X".$singleInfo['gid']."X"
                                 . $singleInfo['qid'];
                $compoundValue[] = $constSid."X".$singleInfo['gid']."X"
                                 . $singleInfo['qid']."_"."filecount";
           }
           else {
               $compoundValue[] = $constSid."X".$singleInfo['gid']."X"
                                . $singleInfo['qid'] ;
               if ($singleInfo['other'] == "Y")
               {
                   $compoundValue[] = $constSid."X".$singleInfo['gid']."X"
                                    . $singleInfo['qid']."other";
               }
           }		
        }
    return $compoundValue;
    }
    
    /**
     * This function retrieve answer of passing question column names.
     * 
     * @arguments column_names,answer_table,where_column,selected_value
     * 
     * @return    answer_of_columnvalue
     */	
    function displayAnswer($columnName, $table, $column)
    {	
        global $quesTable;
        global $ansTable;
        foreach($columnName as $singleColumn)
        {
            $selectRow = mysql_query("SELECT `$singleColumn` from `$table` "
                       . "WHERE `$column` = 'gottarocknow@gmail.com'");
            while($fetchRow = mysql_fetch_array($selectRow))
            {
                if (($fetchRow[$singleColumn] == "A1")
                    || ($fetchRow[$singleColumn] == "A2")
                ) {				
                    $aDivColm = explode("X", $singleColumn);
                    $selectARows = mysql_query("SELECT answer from `$ansTable` "
                                 . "WHERE `qid` = '$aDivColm[2]' "
                                 . "AND `code` = '$fetchRow[$singleColumn]'");		
                    while($fetchARows = mysql_fetch_array($selectARows))
                    {
                        $collectInfo[] = $fetchARows['answer'];
                    }
                  }
                elseif ($fetchRow[$singleColumn] == "Y")
                {
                    $yDivColm = explode("X", $singleColumn);
                    $selectY = mysql_query("SELECT `type` from `$quesTable` "
                             . "WHERE `gid` = '$yDivColm[1]' "
                             . "AND `qid` = '$yDivColm[2]'");
                    while ($fetchY = mysql_fetch_array($selectY))
                    {
                        $yAnswer = $fetchY['type'];
                    }
                    if ($yAnswer == "M")
                    {
                    $count = $singleColumn[8];
                    $explodeTitle = explode("S", $yDivColm[2]);
                    $RowTitle = "S".$explodeTitle[1];
                    $selectYRows = mysql_query("SELECT `question` from `$quesTable` "
                                 . "WHERE `parent_qid` = '$count' "
                                 . "AND `gid` = '$yDivColm[1]' "
                                 . "AND `title` = '$RowTitle'");
                    while($fetchYRows = mysql_fetch_array($selectYRows))
                    {
                        $collectInfo[] = $fetchYRows['question'];
                    }
                    }
                    else
                    {
                        $collectInfo[] = $fetchRow[$singleColumn];
                    }               
                }
                else {
                    $collectInfo[] = $fetchRow[$singleColumn];
                }
            }
        }
    return $collectInfo;
    }
}
?>
