<?php 
$importData = $_POST['create_map'];
$event_cycle_id = $importData['event_cycle_id'];
$floor_data = $importData['floor_data'];

include '../../config/db.php'; 

$test = new db();
$inscon = $test->exportConnect();


$hall = 'Hall';
$floor = 'Floor';
$file ='blank_template.swf';
$scale = 0;
$xml_booth_sizes ='<boothsizes>
	  <size w="10" h="10" default="1"/>
	  <size w="10" h="20" default=""/>
	  <size w="10" h="30" default=""/>
	  <size w="10" h="40" default=""/>
	  <size w="20" h="10" default=""/>
	  <size w="20" h="20" default=""/>
	  <size w="20" h="30" default=""/>
	  <size w="20" h="40" default=""/>
	  <size w="30" h="10" default=""/>
	  <size w="30" h="20" default=""/>
	  <size w="30" h="30" default=""/>
	  <size w="30" h="40" default=""/>
	  <size w="30" h="40" default=""/>
	  <size w="40" h="10" default=""/>
	  <size w="40" h="20" default=""/>
	  <size w="40" h="30" default=""/>
	  <size w="40" h="40" default=""/>
	</boothsizes>';

$xml_booth_styles ='<boothstyles>
	  <style name="Default" color="0x90C12D" border="0x6C9121" labelcolor="0x000000" uselabel="1" type="Attendee"/>
	  <style name="Available" color="0x90C12D" border="0x6C9121" labelcolor="0x000000" uselabel="1" type="Exhibitor"/>
	  <style name="Rented" color="0xDADADA" border="0xBFBFBF" labelcolor="0x000000" uselabel="1" type="Exhibitor"/>
	  <style name="Pending" color="0xE6BB14" border="0xCB8B2C" labelcolor="0x000000" uselabel="1" type="Exhibitor"/>
	  <style name="Reserved" color="0x367FA8" border="0x2E4E70" labelcolor="0x000000" uselabel="1" type="Exhibitor"/>
	</boothstyles>';

$xml_map ='<map scale="1.0">
	  <floorplan x="" y="" w="" h="" type="" rotation="0" >
	  <topology>'.$floor_data.'</topology>
	  </floorplan>
	  <mask x="" y="" w="" h="" enabled="0" />
	  <background x="" y="" w="" h="" />
	</map>';

$facility_id = 315;


$query = "Insert into maps (event_cycle_id,hall,floor,file,scale,xml_booth_sizes,xml_booth_styles,xml_map,facility_id) values ($event_cycle_id,'$hall','$floor','$file',$scale,'$xml_booth_sizes','$xml_booth_styles','$xml_map',$facility_id)";
$map_id = $test->do_insert($query);

echo json_encode($map_id);