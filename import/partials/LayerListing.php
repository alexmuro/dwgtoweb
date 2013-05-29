<link href="../resources/js/select2.css" rel="stylesheet"/>
<script src="../resources/js/select2.js"></script>
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
$(document).ready(function() { 
  	$("#file_select").select2({ width: '370px' });
  	$('#export').on('click',function(){
  	var floor_layers = [],
  	booth_layers = [],
  	booth_numbers = [];

  		$.each($('.layer_select'),function(){
  			
  			if($(this).val()=='floor'){floor_layers.push($(this).attr('data-layerid'));}
  			else if($(this).val()=='booths'){booth_layers.push($(this).attr('data-layerid'));}
  			else if($(this).val()=='booth_num'){booth_numbers.push($(this).attr('data-layerid'));}
  				
  		});
        console.log(floor_layers);
        console.log(booth_layers);
        console.log(booth_numbers);
  		$.ajax({
            url: '../json2booth/index.php',
            data: {booths:booth_layers,floor:floor_layers},
            type: 'POST',
            beforeSend: function () {
                $('<p/>').text('Converting...').insertAfter('#Export');
            }
            }).done(function(data) {
                  $('<p/>').html('Conversion complete. ').insertAfter('#export');
                  $('<p/>').html(data).insertAfter('#export');                        
            }).fail(function(e) { 
                    ('<p/>').text('Error Map Converting.').insertAfter('#export').addClass('message');  
                console.log('error');
                console.log(e);
        });

  	});
});

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
		<button id='export'>Export Map</button>
	</div>
	<?php include '../upload/uploader.php'; ?>
</div>