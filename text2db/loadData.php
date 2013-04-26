<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../config/db.php'; 

date_default_timezone_set('America/New_York');

$file_extent;
$layer_extent;

function loadFile($filename)
{
  $fileID = checkFile($filename);
  if(!$fileID)
    return 0;

  $lines = 0;
  $handle = fopen($filename, "r");
  while(!feof($handle)){
    $line = fgets($handle);
    $lines++;
  }
  
  fclose($handle);
    
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
    $sql = "Insert into `import_files` (name) values ('$filename')";
    $mapid = $test->do_insert($sql);
    $layerid = 0;
    $layers = array();
    $layerids = array();
    $currentLayer = '';
    $text = 0;$poly=0;$line = 0;

  	while(!feof($fh))
  	{
  		
  		$theData = fgets($fh);
  		$type = strtok($theData,' ');
      
      //echo $type."<br>";
      switch ($type) 
      {
        case "TEXT":
          $text++;
          //echo parseText($theData,$layerid)."<br><br>";
          $test->do_query(parseText($theData,$layerid));
          break;
        case "Polyline":
          $poly++;
          //echo parsePolyline($theData,$layerid)."<br><br>";
          $test->do_query(parsePolyline($theData,$layerid));
          break;
        case "LINE":
          $line++;
          //echo parseLine($theData,$layerid)."<br><br>";
          $test->do_query(parseLine($theData,$layerid));
          break;
        case "LAYER:":
          $inlayer = trim(substr($theData,7));
          if(!in_array($inlayer,$layers))
          {
            $layers[] = $inlayer;
            $currentLayer = $inlayer;
            $sql = "Insert into `import_layers` (`file_id`,`name`,`num_poly`,`num_text`,`num_lines`,`layer_extent`) 
                    values ($mapid,'$currentLayer',$poly,$text,$line,'".json_encode($layer_extent)."')";
            $layerid = $test->do_insert($sql);
            $layerids[$inlayer] = $layerid;
            global $layer_extent;
            global $file_extent;
            echo 'Layer Extent:'.json_encode($layer_extent).'<br>';
            echo 'File Extent:'.json_encode($file_extent).'<br>';
            echo 'Poly:'.$poly.' Text:'.$text.' Line:'.$line.'<br>';
            
            $text = 0;$poly=0;$line = 0;
            unset($GLOBALS['layer_extent']);  
            echo "<h2>$currentLayer</h2>";
          }else{
            $layerid = $layerids[$inlayer];
          }

          break;
      }
      $count++;
      //echo "$count / $lines </br>";
      flush();

  	}
  }
  $sql = "update import_files set file_extent = '".json_encode($file_extent)."' where id = ".$mapid;
  $test->do_query($sql);
  fclose($fh);
  echo json_encode($file_extent);
  echo "Loading $filename completed.";
}

function parseText($input,$mapid)
{
  $type = strtok($input,' ');
  strtok(' ');
  $xy = strtok(' ');
  $out = parseXY($xy);
  $coord[0]['x'] = $out[0];
  $coord[0]['y'] = $out[1];
  $meta = strrev(strtok(strrev($input), ':'));
  $output = "Insert into `import_objects` (layer_id,type,geodata,meta)  values ( $mapid ,'".mysql_real_escape_string($type)."','".mysql_real_escape_string(json_encode($coord))."','".mysql_real_escape_string($meta)."')";
  //echo 'text'.$output.'<br>';
  return $output;
}

function parsePolyline($input,$mapid){
  $type = strtok($input,' ');
  $xyset = strrev(strtok(strrev($input), ':'));
  $coords;
  $xy = strtok($xyset,' ');
  $i = 0;
  while ($xy) {   
      $out = parseXY($xy);   
      $coord[$i]['x'] = $out[0];
      $coord[$i]['y'] = $out[1];
      $i++;
      $xy= strtok(' ');
    }
    $output = "Insert into `import_objects` (layer_id,type,geodata)  values ( $mapid ,'".mysql_real_escape_string($type)."','".mysql_real_escape_string(json_encode($coord))."')";
  return $output;
}

function parseLine($input,$layerid){
  $type = strtok($input,' ');
  $coords;
  $xy1 = strrev(strtok(strrev($input), '('));
  $out = parseXY($xy1);
  $coord[0]['x'] = $out[0];
  $coord[0]['y'] = $out[1];
  $xy2 = strrev(strtok('('));
  $out = parseXY($xy2);
  $coord[1]['x'] = $out[0];
  $coord[1]['y'] = $out[1];
  $output = "Insert into `import_objects` (layer_id,type,geodata)  values ( $layerid ,'".mysql_real_escape_string($type)."','".mysql_real_escape_string(json_encode($coord))."')";
  return $output;
}

function parseXY($xy){
  $xstart = 0;
  $xend = strpos($xy,',');
  $ystart = $xend+1;
  $yend = strpos($xy,')');
  $x = substr($xy,$xstart,$xend-$xstart);
  $y = substr($xy,$ystart,$yend-$ystart);
  if($x[0] == '('){
     $x = substr($x,1); 
  }
  if($y[0] == '('){
     $y = substr($y,1); 
  }
  global $file_extent;

  if(!isset($file_extent[0]) || $x > $file_extent[0]){
    $file_extent[0] = $x;
  }
  if(!isset($file_extent[1]) || $x < $file_extent[1]){
    $file_extent[1] = $x;
  }
  if(!isset($file_extent[2]) || $y > $file_extent[2]){
    $file_extent[2] = $y;
  }
  if(!isset($file_extent[3]) || $y < $file_extent[3]){
    $file_extent[3] = $y;
  }

  global $layer_extent;

  if(!isset($layer_extent[0]) || $x > $layer_extent[0]){
    $layer_extent[0] = $x;
  }
  if(!isset($layer_extent[1]) || $x < $layer_extent[1]){
    $layer_extent[1] = $x;
  }
  if(!isset($layer_extent[2]) || $y > $layer_extent[2]){
    $layer_extent[2] = $y;
  }
  if(!isset($layer_extent[3]) || $y < $layer_extent[3]){
    $layer_extent[3] = $y;
  }


  return [$x,$y];
}

function checkFile($filename)
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
$file = $_GET['file'];
echo 'y'.$file.'y';
echo 'x'.str_replace("'", "", $file).'x';
loadFile(str_replace("'", "", $file));
?>
