<?php
include_once "create table.php";
date_default_timezone_set('America/New_York');



function do_query($sql,$line) {

	//echo "Do Query DB: $db <br>";
	@mysql_select_db($db);
    $result = @mysql_query($sql);
    $total = @mysql_num_rows($result);
    if (@mysql_error() <> "") {			
        echo " <br><font face=\"Verdana\" size=\"1\"><small><b><p align=\"center\">Sorry, there has been an unexpected database error. The webmaster has been informed of this error.</p></b></small></font>";		
        // Error number		
        $error_message = "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"border: 1px solid #bbbbbb;\" bgcolor=\"#ffffff\" width=\"80%\" align=\"center\"><tr><td align=\"right\" width=\"25%\"><font face=\"Verdana\" size=\"1\"><small><b>Error Number:</b></small></font></td><td width=\"75%\"><font face=\"Verdana\" size=\"1\"><small>" . @mysql_errno() . "</small></font></td></tr>";
        // Error Description
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Error Description:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . @mysql_error() . "</small></font></td></tr>";
        // Error Date / Time	
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Error Time:</b></small></font></td><td><font face='Verdana' size='1'><small>" . date("H:m:s, jS F, Y") . "</small></font></td></tr>";		
        // Script	
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Script:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $_SERVER["SCRIPT_NAME"] . "</small></font></td></tr>";
        // Line Number
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Line:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $line . "</small></font></td></tr></table>";
        // SQL	
		
        $error_message .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"border: 1px solid #bbbbbb;\" bgcolor=\"#ffffff\" width=\"80%\" align=\"center\"><tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Query:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $sql . "</small></font></td></tr>";		
        $error_message .= "<tr><td align=\"right\" valign=\"top\" width=\"25%\"><font face=\"Verdana\" size=\"1\"><small><b>Processes:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>";
			
        $result = @mysql_list_processes();
        while ($row = @mysql_fetch_assoc($result)){
            $error_message .= $row["Id"] . " " . $row["Command"] . " " . $row["Time"] . "<br>";
        }
        @mysql_free_result($result);
			
        $error_message .= "</small></font></td></tr></table>";	
		echo "<br>".$error_message."<br>";
			
        //mail($email, "[MySQL Error] ". $client, $error_message, $headers);
        die();
    }
		
    return $result;
}
function strcat() {
  $args = func_get_args() ;
    
  // Asserts that every array given as argument is $dim-size.
  // Keys in arrays are stripped off.
  // If no array is found, $dim stays unset.
  foreach($args as $key => $arg) {
    if(is_array($arg)) {
      if(!isset($dim))
        $dim = count($arg) ;
      elseif($dim != count($arg))
        return FALSE ;
      $args[$key] = array_values($arg) ;
    }
  }
        
  // Concatenation
  if(isset($dim)) {
    $result = array() ;
    for($i=0;$i<$dim;$i++) {
      $result[$i] = '' ;
      foreach($args as $arg)
        $result[$i] .= ( is_array($arg) ? $arg[$i] : $arg ) ;
    }
    return $result ;
  } else {
    return implode($args) ;
  }
}

function checkFile($filename,$fips)
{
  echo "<br>Check File<br>";
  if (file_exists($filename)) { 
	$path_parts = pathinfo($filename);
	echo "loc:".$path_parts['dirname']."<br>";
	echo "file:".$path_parts['basename']."<br>";
	echo "size : " . filesize($filename) . " bytes<br>";
	echo "last modified: " . date ("m/d/y H:i:s.", filemtime($filename))."<br>";
	$db = "soundtos_dataMeta";
	@mysql_select_db($db);
	$sql ="select name from files where name = '".$path_parts['basename']."'";
	$result = @mysql_query($sql);
	$num_rows = @mysql_num_rows($result);
	if($num_rows != 0)
	{
	  echo "This file has alreay been processed";
	  return 0;
	}
	$sql = "insert into files (fips,name,location,size,lastModified,dateProcessed) values ($fips,'".$path_parts['basename']."','".$path_parts['dirname']."',".filesize($filename).",'".date ("m/d/y H:i:s", filemtime($filename))."',NOW())";
	echo $sql."<br>";
	@mysql_query($sql) or die("<br>".$insertsql."</b><br>".@mysql_error());
	return @mysql_insert_id();
	}
   else
   {
	 echo $filename." does not exist.";
	 return 0;
   }
  
}

function loadFile($filename,$infips)
{
   
 
	$inscon = mysql_connect("localhost","root","am1238wk");
	if (!$inscon)
	{
		die('Could not connect: ' . mysql_error());
	}
	
	$fileID = checkFile($filename,$infips);
	if(!$fileID)
		return 0;
	
	

	$lines = filesize($filename)/100;
	$fh = fopen($filename, 'r');
	$total_count = 0;
	$count = 0;
	$tempCount = 0;

	if ($fh) 
	{
		echo "Loading $filename. <br>";
		while(!feof($fh))
		{
			if($total_count > 0 && ($curstid != $stid || $curyear != $year || $curmonth != $month))
			{
				$insertsql = "Insert into loaded (fileID,FIPS,stationID,year,month,count) values ('$fileID',$curFIPS,'$curstid',$curyear,$curmonth,$tempCount)";
				$db = "soundtos_dataMeta";
				mysql_select_db($db);
				mysql_query($insertsql,$inscon) or die("<br>".$insertsql."</b><br>".mysql_error());
				echo "loaded  stid:$curstid , year:$curyear , month:$curmonth"; 
			}
			$theData = fgets($fh);
			//echo $theData . "<br>";
			while((!feof($fh) && empty($theData)) || $class == '00')
			{
				$theData = fgets($fh);
			}
			
			$raw = substr($theData,19,strlen($theData)-21);
			$fips = substr($theData, 1,2);
			$stid = substr($theData, 3,6);
			$sPattern = '/\s*/m';
			$sReplace = '';
			$stid=preg_replace( $sPattern, $sReplace, $stid );
			$dir = $theData[9];
			$ln = $theData[10];
			$year = substr($theData, 11,2);
			$month = substr($theData, 13,2);
			$day = substr($theData, 15,2);
			$hour = substr($theData, 17,2);
			
			while(strlen(trim($stid," ")) < 6)
			{ 
				$stid = "0".trim($stid," ");
			}

			$curmonth = $month;	
			$curyear = $year;
			$curstid = $stid;
			$curFIPS = $fips;
			$table_name = "st".$curstid;
			$dbcheck = "soundtos_".$infips."_CLASS";
			if(!table_exists($table_name,$dbcheck))
			{
				createRawClassTable($fips,$stid);
			}
			$strsql = "Insert into $table_name (fips,stid,dir,ln,year,month,day,hour,dow,total,class1,class2,class3,class4,class5,class6,class7,class8,class9,class10,class11,class12,class13,class14,class15,originFile) Values ";
 
			$weights[0] = 0;
			$weights[1] = substr($raw,5,5);
			$stpos = 10;
			while($stpos < strlen($raw))
			{
				$weights[($stpos/5)] = substr($raw, $stpos,5);
				$stpos +=5;
			}
			$weights[11] =0;
			$weights[12] =0;
			$weights[13] =0;
			$weights[14] =0;
			$weights[15] =0;
			$date = mktime(0, 0, 0, $month,$day, $year);
			$values = "(".$fips .",'". $stid  ."',". $dir .",". $ln .",". $year .",". $month .",". $day  .",". $hour  .",". date("w", $date); 
			if($fips == $infips)
			{
				$strsql = strcat($strsql,$values);
				$stpos = 0;
				while($stpos < (strlen($raw)/5) && $stpos < 16 )
				{
					//echo $weights[$stpos]."x<br>";
					if(empty($weights[$stpos]) || $weights[$stpos] == '\n' || $weights[$stpos] == '\r')
					{
						$axleweight = ",0";
					}
					else
					{
						$axleweight = ",". $weights[$stpos]; 
					}
					$stpos++;
					$strsql = strcat($strsql,$axleweight);
				}
				while($stpos < 16)
				{
					$axleweight = ",0";
					$stpos++;
					$strsql = strcat($strsql,$axleweight);
				}			
				$end = ",$fileID),";
				$strsql = strcat($strsql, $end);
			}
			while ($curstid == $stid && $curyear == $year && $count < 1000 && $curmonth == $month)// Loop til end of file.
			{
				set_time_limit(30);
				//create variables
				$weights[26] =0;

				$theData = fgets($fh);
				//echo $theData . "<br>";
				while((!feof($fh) && empty($theData)))
				{
					$theData = fgets($fh);
				}
				$raw = substr($theData,19,strlen($theData)-21);
				$fips = substr($theData, 1,2);
				$stid = substr($theData, 3,6); 
				$sPattern = '/\s*/m';
				$sReplace = '';
				$stid=preg_replace( $sPattern, $sReplace, $stid );
				$dir = $theData[9];
				$ln = $theData[10];
				$year = substr($theData, 11,2);
				$month = substr($theData, 13,2);
				$day = substr($theData, 15,2);
				$hour = substr($theData, 17,2);
				
				while(strlen(trim($stid," ")) < 6)
				{ 
				$stid = "0".trim($stid," ");
				}
	
				$weights[0] = 0;
				$weights[1] = substr($raw,5,5);
				$stpos = 10;
				//echo "raw '" .$raw ."'<br>";
				while($stpos < strlen($raw))
				{
					$weights[($stpos/5)] = substr($raw, $stpos,5);
					$stpos +=5;
				}
				$weights[11] =0;
				$weights[12] =0;
				$weights[13] =0;
				$weights[14] =0;
				$weights[15] =0;
				$count++;
				//echo $theData . "<br>";
				$date = mktime(0, 0, 0, $month,$day, $year);
				$values = "(".$fips .",'". $stid  ."',". $dir .",". $ln .",". $year .",". $month .",". $day  .",". $hour  .",". date("w", $date); 
				if($fips == $infips && !empty($stid))
				{
					$strsql = strcat($strsql,$values);
					$stpos = 0;
					while($stpos < (strlen($raw)/5) && $stpos < 16 )
					{
						//echo $weights[$stpos]."x<br>";
						if(empty($weights[$stpos]) || $weights[$stpos] == "\r" )
						{
							//echo "why am i Here?";
							$axleweight = ",0";
						}
						else
						{
							$axleweight = ",". $weights[$stpos];
						}
						$stpos++;
						$strsql = strcat($strsql,$axleweight);
					}	
					while($stpos < 16)
					{
						$axleweight = ",0";
						$stpos++;
						$strsql = strcat($strsql,$axleweight);
					}
					$end = ",$fileID),";
					$strsql = strcat($strsql, $end);
					//echo $count;
				}
			}
			$sql = substr($strsql,0,-1);
			//echo "Inserting $count lines into $table_name <br>";
			if($table_name != "st_")
			{
				//echo $sql."<br>";
				$db = "soundtos_".$infips."_CLASS";
				do_query($sql,$db, __LINE__);
				$total_count += $count;
				echo "Completed $total_count of $lines lines in $table_name.<br>";
			}
			$tempCount = $count;
			$count =0;
		}
	}

fclose($fh);
echo "Loading $filename completed.";
}

for($year =9; $year <=11; $year++)
{
	$yearString = $year;
	if($year < 10)
	{
		$yearString = "0".$year;
	}	

	for($month = 1; $month<=12;$month++)
	{
		$monthString = $month;
		if($month < 10)
		{
			$monthString = "0".$month;
		}	
		loadFile("data/36-NY/C36_".$yearString."_".$monthString.".TXT","36");
	}
}
?>
