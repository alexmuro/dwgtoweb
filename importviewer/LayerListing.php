<html>
<head>
<title>DWG Imported File Viewer</title>
<script src='../js/jquery.min.js'></script>
<script src='../js/getJson.js'></script>
<script>
jQuery(document).ready(function(){



$("#test").click(function(){
	console.log('Button Press');
	console.log('get data extent:'+vectors.getDataExtent());
	console.log('get extent:'+vectors.getExtent());
	console.log(vectors.features);
	console.log(vectors);
	map.zoomToExtent(vectors.getDataExtent());
	console.log(map.getZoom());

});	

$("#file_select").change(function() {
	var url = '../models/getImportFile.php?id='+$(this).val();
	console.log(url);
	var filedata = getJson(url);
	console.log(filedata);
	var output = '';
	for (var layer = 0; layer < filedata.length; layer++)
	{
		output += "<div class='layer_listing' value='"+filedata[layer]['id']+"'>"+filedata[layer]['id']+"-"+filedata[layer]['name']+":</div>";
	}
	$('#file_listing').html(output); 

	$(".layer_listing").click(function(){
	console.log('layer clicked');
	console.log($(this).attr('value'));

	var layerurl = "../models/getLayerGeoJSON.php?layer="+ $(this).attr('value');

	if(!$(this).hasClass('added'))
	{
		$(this).addClass('added');
		console.log('add class');
		var newLayer = new OpenLayers.Layer.Vector($(this).attr('value'), {
                    strategies: [new OpenLayers.Strategy.Fixed()],                
                    protocol: new OpenLayers.Protocol.HTTP({
                        //url: "test.json",
                        url: layerurl,
                        format: new OpenLayers.Format.GeoJSON()
                    }),
                    styleMap: styles,
                    renderers: ["Canvas", "SVG", "VML"]
                });
        
			map.addLayer(newLayer);
			console.log(newLayer.getDataExtent());
	}
	else
	{
		//$(this).removeClass('added');
		console.log(map.getLayersByName($(this).attr('value')));
		console.log(map.getLayersByName($(this).attr('value'))[0].getDataExtent());
		map.zoomToExtent(map.getLayersByName($(this).attr('value'))[0].getDataExtent());
		//map.removeLayer(map.getLayersByName($(this).attr('value'))[0]);
		//console.log('remove class');
	}
});

 });
});
</script>
<style>
.added{
	color:#000;
	background-color: #ccc;
}
</style>
</head>
<body>
<div id="wrapper">
	<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	include '../config/db.php';

	$data = new db();
	$inscon = $data->importConnect();

	$sql = "select id,name from import_files";
	$rs = $data->do_query($sql);?>

	<div id='left_control'>
		Select Map:
		<select id='file_select'>
		<?php
		while ( $row = mysql_fetch_assoc( $rs )) {
			$id = $row['id'];
			$name = $row['name'];
			echo "<option value ='$id'>$id - $name</option>";      
		      
		    }
		?>
		</select>

		<div id="file_listing">
		</div>
		<button id='test'>Run</button>
	</div>
	
</body>
</html>