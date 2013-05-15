<?php
$booths = $_POST['booths'];
$map_id = $_POST['map_id'];
//echo json_encode($booths);
include '../../config/db.php'; 
$test = new db();
$inscon = $test->importConnect();
$query = 'insert into booths (map_id,x,y,w,h,num,outer_points) values ';
$num = 'boothnumber';


for($i = 0;$i < count($booths);$i++){

	$query .= "($map_id,".$booths[$i]['x'].",".$booths[$i]['y'].",".$booths[$i]['w'].",".$booths[$i]['h'].",'".$booths[$i]['num']."','".$booths[$i]['outer_points']."'),";

}
$query =substr($query, 0, -1);
echo json_encode($test->do_insert($query));

$query = 'UPDATE booths 
SET xml = 
concat("<booth id=\"",
    id,
    "\" x=\"",x,
    "\" y=\"",y,
    "\" h=\"",h,
    "\" w=\"",w,
    "\" num=\"",num,
    "\" row=\"\" 
    aisle=\"\" 
    style=\"Default\"  
    reservedstyle=\"\" 
    availability=\"Availability\" 
    outerpoints=\"",outer_points,
    "\" />")
WHERE map_id = '.$map_id;
$test->do_query($query);

//$xml ="