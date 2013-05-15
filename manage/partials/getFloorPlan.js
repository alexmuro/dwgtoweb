function getFloorPlan(map_id)
{
  var url = "../data/read/getFloorPlan.php?mid="+map_id;
  //var url ='http://localhost/njtransit/data/states/34/topo_census_tracts_sf1.json';
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