<?php
ini_set("memory_limit","1024M");


$map_id = $_GET["mid"];

include '../../config/db.php'; 
$test = new db();
$inscon = $test->importConnect();

$output ['type'] = 'FeatureCollection';
 
//Sql call & json encod
$sql = "SELECT id,num,availability,outer_points,x,y FROM booths
        WHERE map_id=$map_id";

$rs=mysql_query($sql) or die($sql."<br><br>".mysql_error());
//$results = array();
while ( $row = mysql_fetch_assoc( $rs )) {
    $output['features'][]=importToGeoJSONFeature(explode(',',$row['outer_points']),$row['id'],$row['num'],$row['availability'],$row['x'],$row['y']);
}

echo json_encode($output).PHP_EOL; 

function importToGeoJSONFeature($geodata,$id,$num,$availability,$x,$y)
{
    $properties['id'] = $id;
    $properties['boothnum'] = $num;
    $properties['availability'] = $availability;
    $feature['type'] = 'Feature';
    $feature['properties'] = $properties;

    $coordinates[] = array();

    for($i = 0;$i < count($geodata);$i+=2)
    {  
        $coordinates[$i/2][0] = $geodata[$i]+$x;
        $coordinates[$i/2][1] = $geodata[$i+1]+$y;
    }

    $geometry['type'] = 'Polygon';
    $geometry['coordinates'][0] = $coordinates;
    $feature['geometry'] = $geometry;

    return $feature;
}
 
?>