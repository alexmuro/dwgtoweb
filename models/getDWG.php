<?php
    //Pass Map ID by Map ID
    $layerID = $_GET["map"];
    
    include '../config/db.php'; 
    $test = new db();
    $inscon = $test->importConnect();
   

    //Sql call & json encode
    $sql = "SELECT import_objects.id, geodata as geo FROM import_objects
            JOIN import_layers 
            ON import_objects.layer_id = import_layers.id 
            WHERE import_layers.file_id= $layerID";

    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    $results = array();
    while ( $row = mysql_fetch_assoc( $rs )) {
      $results[] = $row;
    }
    echo json_encode($results); 
?>