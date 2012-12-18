<?php
    //Pass Map ID by Map ID
    $layerID = $_GET["map"];
    
    include '../config/db.php'; 
    $test = new db();
    $inscon = $test->connect();
   

    //Sql call & json encode
    $sql = "select num as boothnum,availability,style,x,y,w,h from tbl_booths where map_id = $layerID";
    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    $results = array();
    while ( $row = mysql_fetch_assoc( $rs )) {
      $results[] = $row;
    }
    echo json_encode($results); 
?>
