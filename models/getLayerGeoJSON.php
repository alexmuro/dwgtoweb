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
 $feature['geometry'] = json_decode($geodata);

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