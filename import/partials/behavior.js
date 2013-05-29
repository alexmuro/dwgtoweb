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
		var extent = [filedata['file_extent'][0],filedata['file_extent'][3],filedata['file_extent'][1],filedata['file_extent'][2]];
		console.log(filedata['file_extent']);
		console.log(map.getExtent());
		//map.zoomToExtent(extent);
		
		var count = 0;
		$.each(filedata, function(k, v) { count++; });
		var output = '<table id="layer_table" class="tablesorter"><thead><tr><th>Layer Name</th><th>label</th><th>select</th></tr></thead><tbody>';
		for (var layer = 0; layer < count-1; layer++)
		{
			output += "<tr><td><div class='layer_listing' value='"+filedata[layer].id+"'>"+filedata[layer].id+"-"+filedata[layer].name.substring(0,12)+":</div></td><td><input type='checkbox' class='labelcheck' data-layerid='"+filedata[layer].id+"'></td><td><select data-layerid='"+filedata[layer].id+"' class='layer_select'><option value='none'></option><option value='floor'>Floor</option><option value='booths'>Booths</option><option value='booth_num'>Booth Numbers</option></select></td></tr>";
		}
		output+='</tbody></table>'
		$('#file_listing').html(output);
		$("#layer_table").tablesorter();
		
		$('.labelcheck').on('change',function(){
			console.log('labelcheck on change');
			if($(this).is(':checked')){
			
				var styles = new OpenLayers.StyleMap({
				    "default": new OpenLayers.Style({
				        strokeWidth: 2,
				        fillColor:'#ffccaa',
				        //label details
				        label : "${getLabel}",   
				        fontColor: "black",
				        fontSize: "10px",
				        fontFamily: "Courier New, monospace",
				        fontWeight: "bold",
				        labelXOffset: "0",
				        labelYOffset: "0",
				        labelOutlineColor: "white",
				        labelOutlineWidth: 3
				    },
				    {
				        context: {
				            getLabel: function(feature) {
				                if(String(feature.attributes.meta).trim() == "null") {
				                    return "";
				                }
				                else{
				                    
				                    return feature.attributes.meta;
				                }

				            }
				        }
				    }) 
				});
				labeled_layer = map.getLayersByName($(this).attr('data-layerid'))[0];
				labeled_layer.styleMap = styles;
				labeled_layer.redraw();
			}else{
				var styles = new OpenLayers.StyleMap({
				    "default": new OpenLayers.Style({
				        strokeWidth: 2,
				        fillColor:'#ffccaa'
				    })
				});
				labeled_layer = map.getLayersByName($(this).attr('data-layerid'))[0];
				labeled_layer.styleMap = styles;
				labeled_layer.redraw();
			}

		});
		$(".layer_listing").click(function()
		{
			console.log('layer clicked');
			console.log($(this).attr('value'));
			var layerurl = "../models/getLayerGeoJSON.php?layer="+ $(this).attr('value');
			//layerurl= "../text2db/txts/surfshp/outline.geojson"
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
						console.log('projection:');
						console.log(map.getProjectionObject());
						console.log('load end and move- Extent:');
						console.log(newLayer);
						map.zoomToExtent(extent);
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