<?php 
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 300);
	
	$inputfile = $_POST['file'];
	exec("cat $inputfile",$output,$return);
	$Layers = array();
	foreach($output as $val){
		$feature = json_decode(rtrim($val,','),true);
		if($feature['type'] == 'Feature'){			
			$data['meta'] = $feature['properties']['Text'];
			$data['geometry']=json_encode($feature['geometry']);
			$Layers[$feature['properties']['Layer']][] = $data;
		}
	}

	include '../config/db.php'; 
	$db_conn = new db();
    $db_conn->importConnect();

	$insert_file = "Insert into import_files (`name`) values ($inputfile)";
	$file_id =$db_conn->do_insert($insert_file);

	foreach ($Layers as $layer_name => $objects) {

		$insert_layer = "Insert into `import_layers` (`file_id`,`name`,`num_poly`) 
                    values ($file_id,'".mysql_real_escape_string($layer_name)."',".count($objects).")";
        $layerid = $db_conn->do_insert($insert_layer);
		//echo $layer_name.'-'.count($objects).':<br>';
        
        $insert_object = "Insert into `import_objects` (layer_id,geodata,meta) values " ;
        $i=0;
        foreach ($objects as  $object) {
        	if($i < 500){
        		$insert_object .= " ($layerid,'".$object['geometry']."','".mysql_real_escape_string($object['meta'])."'),";        		
        		$i++;			
        	}
        	else {
        		$db_conn->do_insert(substr($insert_object,0,-1));	
        		$i = 0;
        		$insert_object = "Insert into `import_objects` (layer_id,geodata,meta) values " ;
        		$insert_object .= "($layerid,'".$object['geometry']."','".mysql_real_escape_string($object['meta'])."'),";
        	}		
        }
        $db_conn->do_insert(substr($insert_object,0,-1));
	}
	echo "$inputfile loaded;"
?>