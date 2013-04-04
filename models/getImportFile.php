<?php
    //Pass Map ID by Map ID
    $id = $_GET["id"];
    
    include '../config/db.php'; 
    $test = new db();
    $inscon = $test->importConnect();
   
    $sql = "select file_extent from import_files where id = $id";
    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    $results = array();
    while ( $row = mysql_fetch_assoc( $rs )) {
      $results['file_extent'] = json_decode($row['file_extent']);
    }


    //Sql call & json encode
    $sql = "select * from import_layers where file_id = $id";
    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    while ( $row = mysql_fetch_assoc( $rs )) {
      $results[] = $row;
    }


    echo json_encode($results); 
?>