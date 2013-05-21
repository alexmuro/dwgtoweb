function getBooths(map_id)
{
  var url = "../data/read/booth2GeoJson.php?mid="+map_id;
  //var url ='http://localhost/maps/asGeoJSON/surf_outline.topojson';
  var name = 'FloorPlan';
    
    var vectorlayer = new OpenLayers.Layer.Vector(name, { 
        strategies: [new OpenLayers.Strategy.Fixed()],                
        protocol: new OpenLayers.Protocol.HTTP({
            url: url,
            format: new OpenLayers.Format.GeoJSON(),
            renderers: ["Canvas", "SVG", "VML"]
        })
	});  
  return vectorlayer;
}