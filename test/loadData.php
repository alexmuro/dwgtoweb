<?php
include '../config/db.php'; 

date_default_timezone_set('America/New_York');
function checkFile($filename,$fips)
{
  echo "<br>Check File<br>";
  if (file_exists($filename)) { 
  $path_parts = pathinfo($filename);
  echo "loc:".$path_parts['dirname']."<br>";
  echo "file:".$path_parts['basename']."<br>";
  echo "size : " . filesize($filename) . " bytes<br>";
  echo "last modified: " . date ("m/d/y H:i:s.", filemtime($filename))."<br>";
  return 1;
  }
  else
  {
    echo $filename." does not exist.";
    return 0;
  }
}

function loadFile($filename,$mapid)
{
  $fileID = checkFile($filename,$infips);
  if(!$fileID)
    return 0;

  
  $lines = 0;
  $handle = fopen($filename, "r");
  while(!feof($handle)){
    $line = fgets($handle);
    $lines++;
  }
  fclose($handle);

  echo $linecount;

    
  $fh = fopen($filename, 'r');
  $tempCount = 0;
  $count = 0;
  $total_count = 0;
  echo "# Objects: $lines <br>";
  echo "Type    GEO     Meta<br>";

  if ($fh) 
  {
    $test = new db();
    $inscon = $test->importConnect();
    echo "Loading $filename. <br>";
  	while(!feof($fh))
  	{
  		
  		$theData = fgets($fh);
  		$type = strtok($theData,' ');
      //echo $type."<br>";
      switch ($type) 
      {
        case "TEXT":
          echo $theData."<br>";
          echo parseText($theData,$mapid)."<br><br>";
          $test->do_query(parseText($theData,$mapid));
          break;
        case "Polyline":
          echo $theData."<br>";
          echo parsePolyline($theData,$mapid)."<br><br>";
          $test->do_query(parsePolyline($theData,$mapid));
          break;
        case "LINE":
          echo $theData."<br>";
          echo parseLine($theData,$mapid)."<br><br>";
          $test->do_query(parseLine($theData,$mapid));
          break;
      }
      $count++;
      echo "$count / $lines </br>";


  	}
  }
  fclose($fh);
  echo "Loading $filename completed.";
}

function parseText($input,$mapid)
{
  $type = strtok($input,' ');
  strtok(' ');
  $xy = strtok(' ');
  $xstart = 0;
  $xend = strpos($xy,',');
  $ystart = $xend+1;
  $yend = strpos($xy,')');
  $x = substr($xy,$xstart,$xend-$xstart);
  $y = substr($xy,$ystart,$yend-$ystart);
  $coord;
  if($x[0] == '(')
  {
     $x = substr($x,1); 
  }
  if($y[0] == '(')
  {
     $y = substr($y,1); 
  }
  $coord[0]['x'] = $x;
  $coord[0]['y'] = $y;
  $meta = strrev(strtok(strrev($input), ':'));

  $output = "Insert into `dwgimport` (mapid,objecttype,geo,meta)  values ( $mapid ,'".mysql_real_escape_string($type)."','".mysql_real_escape_string(json_encode($coord))."','".mysql_real_escape_string($meta)."')";

  return $output;
}

function parsePolyline($input,$mapid)
{
  $type = strtok($input,' ');
  $xyset = strrev(strtok(strrev($input), ':'));
  $coords;
  $xy = strtok($xyset,' ');
  $i = 0;
    while ($xy) {
        $xstart = 0;
        $xend = strpos($xy,',');
        $ystart = $xend+1;
        $yend = strpos($xy,')');
        $x = substr($xy,$xstart,$xend-$xstart);
        $y = substr($xy,$ystart,$yend-$ystart);
        if($x[0] == '(')
        {
           $x = substr($x,1); 
        }
        if($y[0] == '(')
        {
           $y = substr($y,1); 
        }
        $coord[$i]['x'] = $x;
        $coord[$i]['y'] = $y;
        $i++;
        $xy= strtok(' ');
    }

  $output = "Insert into `dwgimport` (mapid,objecttype,geo)  values ( $mapid ,'".mysql_real_escape_string($type)."','".mysql_real_escape_string(json_encode($coord))."')";
  return $output;
}
function parseLine($input,$mapid)
{
  $type = strtok($input,' ');
  $coords;
  $xy1 = strrev(strtok(strrev($input), '('));
  $xstart = strpos($xy1,'-');
  $xend = strpos($xy1,',');
  $ystart = $xend+1;
  $yend = strpos($xy1,')');
  $x = substr($xy1,$xstart,$xend-$xstart);
  $y = substr($xy1,$ystart,$yend-$ystart);
  if($x[0] == '(')
  {
     $x = substr($x,1); 
  }
  if($y[0] == '(')
  {
     $y = substr($y,1); 
  }
  $coord[0]['x'] = $x;
  $coord[0]['y'] = $y;

  $xy2 = strrev(strtok('('));
  $xstart = strpos($xy2,'-');
  $xend = strpos($xy2,',');
  $ystart = $xend+1;
  $yend = strpos($xy2,')');
  $x = substr($xy2,$xstart,$xend-$xstart);
  $y = substr($xy2,$ystart,$yend-$ystart);
  if($x[0] == '(')
  {
     $x = substr($x,1); 
  }
  if($y[0] == '(')
  {
     $y = substr($y,1); 
  }
  $coord[1]['x'] = $x;
  $coord[1]['y'] = $y;
  
  $output = "Insert into `dwgimport` (mapid,objecttype,geo)  values ( $mapid ,'".mysql_real_escape_string($type)."','".mysql_real_escape_string(json_encode($coord))."')";
  return $output;
}

loadFile('ahip-booths.txt',5);
?>
