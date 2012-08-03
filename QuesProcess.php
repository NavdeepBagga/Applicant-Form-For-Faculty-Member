<?php

/**
* Retrieve information from survey of Applicant Form for Faculty Member
*        
* @author      Navdeep Bagga
* @authorEmail admin@navdeepbagga.com
* @category    Retrieve Information
* @copyright   Copyright (c) June-July Testing and Consultancy Cell (http://www.navdeepbagga.com)
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
* @copyright  Copyright (c) June-July Testing and Consultancy Cell (http://www.navdeepbagga.com)
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
		$selectCol= mysql_fetch_array(mysql_query("SELECT `$select` From `$table` " 
				. "WHERE `$column`='$title' "
				. "ORDER BY `$select` DESC LIMIT 1;"));
		$fetchCol = $selectCol [$select];	
		return $fetchCol;
	}	
	
	/**
	 * This function retrieves all information like question id, type and group id  of given question
	 * 
	 * @arguments last_question_id, question_table
	 * 
	 * @return    array of gid,qid,type of question
     */		
	function getQuestionInfo($lastQid, $table)
	{
		for ($ques = 1; $ques <= $lastQid; $ques ++)
		{
			$questionQid [] = $ques;
		}
		$counterA = 0;
		foreach($questionQid as $singleQid) 
			{
				$selectQuesInfo = mysql_query("SELECT `qid`, `gid`, `type` From `$table` "
								. "WHERE `qid`='$singleQid'");
				$fetchQuesInfo  = mysql_fetch_array($selectQuesInfo);
				$infoQues [$counterA] [qid]  = $fetchQuesInfo ['qid'];	
				$infoQues [$counterA] [gid]  = $fetchQuesInfo ['gid'];		
				$infoQues [$counterA] [type] = $fetchQuesInfo ['type'];		
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
	function questionColumnCreator ($constSid, $quesInfo, $table)
	{			
		foreach ($quesInfo as $singleInfo)
		{
			if (($singleInfo ['type'] == ";")
				|| ($singleInfo ['type'] == ":")
			) {	
				$selectUnderscoreColm = mysql_query("SELECT DISTINCT `title`, `gid` , `parent_qid` from `$table` "
									. "WHERE `parent_qid`='$singleInfo[qid]' and `gid`='$singleInfo[gid]'");
				while($fetchUnderscoreColm = mysql_fetch_array($selectUnderscoreColm))	
				{							
					$dinstinctInfo ['title'] = $fetchUnderscoreColm ['title'];
					$dinstinctInfo ['gid']   = $fetchUnderscoreColm ['gid'];
					$dinstinctInfo ['parent_qid'] = $fetchUnderscoreColm['parent_qid'];
						$selectRepeatColm = mysql_query("SELECT `title` from `$table` "
										. "WHERE `parent_qid`='$singleInfo[qid]' and `gid`='$singleInfo[gid]' "
										. "GROUP BY `title` HAVING COUNT(*) > 1 ");
						while($fetchRepeatColm = mysql_fetch_array($selectRepeatColm))
							{
								$compoundValue[] = $constSid."X".$dinstinctInfo['gid']."X".$dinstinctInfo['parent_qid']
								. $dinstinctInfo['title']."_".$fetchRepeatColm['title'];
							}	
				}
			}
			elseif (($singleInfo ['type'] == "M")
					|| ($singleInfo ['type'] == "Q")
			) {	
				$selectSqColm = mysql_query("SELECT `title`, `gid`, `parent_qid` from `$table` "
							. "WHERE `parent_qid`='$singleInfo[qid]' and `gid`='$singleInfo[gid]'");
				while($fetchSqColm = mysql_fetch_array($selectSqColm))	
				{
					$compoundValue[] = $constSid."X".$fetchSqColm['gid']."X"
					. $fetchSqColm['parent_qid'].$fetchSqColm['title'];
				}
						
			}
			else {
				$selectSimpleColm = mysql_query("SELECT `title`, `gid`, `qid` from `$table` "
								. "WHERE `qid`='$singleInfo[qid]' and `gid`='$singleInfo[gid]' ");
				while($fetchSimpleColm = mysql_fetch_array($selectSimpleColm))	
					{
						$compoundValue[] = $constSid."X".$fetchSimpleColm['gid']."X".$fetchSimpleColm['qid'];
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
	function displayAnswer($columnName, $table)
	{		
		foreach($columnName as $singleColumn)
		{
			$selectRow = mysql_query("SELECT `$singleColumn` from `$table` "
					. "WHERE `68316X2X31` = 'gottarocknow@gmail.com' ");
			while($fetchRow = mysql_fetch_array($selectRow))
			{
				$collectInfo [] = $fetchRow [$singleColumn];
			} 
		}
	return $collectInfo;
	}
}
?>
