<html>
<head>
<title>DWG Imported File Viewer</title>
<script src='../js/jquery.min.js'></script>
<script src='../js/getJson.js'></script>
<script>
jQuery(document).ready(function(){
$("#file_select").change(function() {
	var url = '/dwgtoweb/models/getImportFile.php?id='+$(this).val();
	console.log(url);
	var filedata = getJson(url);
	console.log(filedata);
	var output = '';
	for (var layer = 0; layer < filedata.length; layer++)
	{
		console.log('sss');

		output += "<span class='layer_listing' value='"+filedata[layer]['id']+"'>"+filedata[layer]['id']+"-"+filedata[layer]['name']+":</span><br>";
	}
	$('#file_listing').html(output); 


 });
});
</script>
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
	</div>
	<div id 
</div>
</body>
</html>