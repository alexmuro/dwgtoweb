<?php 
$map_id = $_GET["mid"];

include '../../config/db.php'; 
$mapdb = new db();
$mapdb->importConnect();
$query = "select xml_map from maps where id = $map_id";
$result = $mapdb->do_query($query);
$row = mysql_fetch_array($result);
$data =objectsIntoArray(simplexml_load_string($row[0]));
echo(trim($data['floorplan']['topojson']));


function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}