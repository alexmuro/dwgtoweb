<?php
    //Pass Map ID by Map ID
    $layerID = $_GET["map"];
    
    include '../config/db.php'; 
    $test = new db();
    $inscon = $test->importConnect();
   

    //Sql call & json encode
    $sql = "select id,geo from dwgimport where mapid = $layerID ";
    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    $results = array();
    while ( $row = mysql_fetch_assoc( $rs )) {
      $results[] = $row;
    }
    echo json_encode($results); 
?>