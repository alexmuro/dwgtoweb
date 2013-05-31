<?php
ini_set("memory_limit","1024M");
 ini_set('display_errors','On');
 error_reporting(E_ALL);
$layerID = $_POST["layers"];

include '../../config/db.php'; 
$test = new db();
$inscon = $test->importConnect();

$layers = implode(",",$layerID);

$output ['type'] = 'FeatureCollection';
 

    //Sql call & json encod
    $sql = "SELECT id,meta,type,geodata as geo FROM maps.import_objects
            WHERE import_objects.layer_id in ($layers)
            ";

    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    $results = array();
    while ( $row = mysql_fetch_assoc( $rs )) {
        
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
?>