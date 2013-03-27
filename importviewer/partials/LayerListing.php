<link href="resources/js/select2.css" rel="stylesheet"/>
<script src="resources/js/select2.js"></script>
<style>
#wrapper{
	height:100%;
	overflow: auto;
}
.added{
	color:#000;
	background-color: #ccc;
}
</style>
<script>
  $(document).ready(function() { $("#file_select").select2(); });
</script>
<div id="wrapper">
<?php
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
	<?php include '../upload/uploader.php'; ?>
</div>