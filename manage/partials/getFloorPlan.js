function getFloorPlan(map_id)
{
  var url = "../data/read/getFloorPlan.php?mid="+map_id;
  var name = 'FloorPlan';
    
    // give the features some style
 
    var vectorlayer = new OpenLayers.Layer.Vector(name, { 
        strategies: [new OpenLayers.Strategy.Fixed()],                
        protocol: new OpenLayers.Protocol.HTTP({
            url: url,
            format: new OpenLayers.Format.TopoJSON(),
            styleMap: getDefaultStyle(),
            renderers: ["Canvas", "SVG", "VML"]
        })
	});  
  return vectorlayer;
}