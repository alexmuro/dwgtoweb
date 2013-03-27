$(document).ready(function() {
	$("#test").click(function()
	{
		console.log("Base Layer");
		console.log(map.getLayersByName('Base Layer')[0].getDataExtent());
	});

	$("#file_select").change(function() 
	{
		var url = '../models/getImportFile.php?id='+$(this).val();
		console.log(url);
		var filedata = getJson(url);
		var output = '';
		for (var layer = 0; layer < filedata.length; layer++)
		{
			output += "<div class='layer_listing' value='"+filedata[layer]['id']+"'>"+filedata[layer]['id']+"-"+filedata[layer]['name']+":</div>";
		}
		$('#file_listing').html(output); 
		$(".layer_listing").click(function()
		{
			console.log('layer clicked');
			console.log($(this).attr('value'));
			var layerurl = "../models/getLayerGeoJSON.php?layer="+ $(this).attr('value');
			console.log(layerurl);

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
					newLayer.events.register("loadend", newLayer, function (e) {
					console.log('load end and move');
					console.log(newLayer.getDataExtent());
					map.zoomToExtent(newLayer.getDataExtent());
					
				});
			}
			else
			{
				$(this).removeClass('added');
				console.log(map.getLayersByName($(this).attr('value')));
				console.log(map.getLayersByName($(this).attr('value'))[0].getDataExtent());
				//map.zoomToExtent(map.getLayersByName($(this).attr('value'))[0].getDataExtent());
				map.removeLayer(map.getLayersByName($(this).attr('value'))[0]);
				//console.log('remove class');
			}
		});//layerlisting on click

 	});//file select on change
});