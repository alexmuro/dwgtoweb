<?php
class show {
  private $id;
  private $name;
  private $nameAbr;
  private $startDate;
  private $endDate;
  private $mapID;
  
  function show($showid)
  {
    $test = new db();
    $inscon = $test->connect();

    //Sql call & json encode
    $sql = "select * from tbl_shows where id = $showid";
    $rs=mysql_query($sql) or die($select."<br><br>".mysql_error());
    $results = array();
    if($row = mysql_fetch_assoc( $rs ))
    {
        //echo "<pre>";
        //print_r($row);
        //echo "</pre>";

        $this->id = $row['id'];
        $this->name = $row['formalname'];
        $this->nameAbr = $row['abbreviation'];
        $this->startDate = $row['startDate'];
        $this->endDate = $row['endDate'];
        $this->mapID = $row['mainmap_id'];
    }
    else 
    {
        return 0;
    }
     
    return 1;
  }
  public function name()
  {
    return $this->name;
  }

  public function map()
  {
    return $this->mapID;
  }

}
    
?>