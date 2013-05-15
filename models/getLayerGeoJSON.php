<?php
ini_set("memory_limit","1024M");
 ini_set('display_errors','On');
 error_reporting(E_ALL);
//Pass Map ID by Map ID
$layerID = $_GET["layer"];

include '../config/db.php'; 
$test = new db();
$inscon = $test->importConnect();



$output ['type'] = 'FeatureCollection';
 

    //Sql call & json encod
    $sql = "SELECT id,meta,type,geodata as geo FROM maps.import_objects
            WHERE import_objects.layer_id= $layerID
            ";

    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    $results = array();
    while ( $row = mysql_fetch_assoc( $rs )) {
        //echo $row['type'].' - '.$row['geo'].'<br>';
    $output['features'][]=importToGeoJSONFeature($row['geo'],$row['type'],$row['id'],$row['meta']);
    }
    echo json_encode($output).PHP_EOL; 

function importToGeoJSONFeature($geodata,$type,$id,$meta)
{


 $properties['id'] = $id;
 $properties['meta'] = $meta;

 $feature['type'] = 'Feature';
 $feature['properties'] = $properties;
 
 if($type == 'TEXT')
 {
    $geometry['type'] = 'Point'; 
 }
 else
 {
    $geometry['type'] = 'Polygon'; 
 }
 $coordinates[] = array();
 
 $geo = json_decode($geodata, true);
 $x = 0;
 foreach($geo as $coords)
 {  

    $coordinates[$x][0] = floatval($coords['x']);
    $coordinates[$x][1] = floatval($coords['y']);
    $x++;
 }

 if($x == 2){

    $geometry['type'] = 'LineString';
    
 }
 if($geometry['type'] == 'Polygon'){ 

    $geometry['coordinates'][0] = $coordinates;

 }
 elseif($geometry['type'] == 'LineString') {

    $geometry['coordinates'] = $coordinates;

 }
 elseif($geometry['type'] == 'Point'){

    $geometry['coordinates'] = $coordinates[0];

 }
 
 if($x > 1 || $geometry['type'] == 'Point'){

    $feature['geometry'] = $geometry;

 }
 //echo '<pre>';
 //print_r($feature);
 //echo '</pre>';
 return $feature;
}


function curl_download($Url){
 
    // is cURL installed yet?
    if (!function_exists('curl_init')){
        die('Sorry cURL is not installed!');
    }
 
    // OK cool - then let's create a new cURL resource handle
     $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $Url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $output = curl_exec($ch);

    return $output;
}    
?>