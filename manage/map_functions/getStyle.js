function getDefaultStyle(type){

    var styles = new OpenLayers.StyleMap({
        "default": {
            strokeWidth: .5,
            strokeColor:'#000',
            fillColor: "#fff",
            fillOpacity: "0.0" 
        },
        "select": {
             strokeColor: "#fff",
             fillColor: "#fff",
            strokeWidth: 4,
            fillOpacity: ".37" 
        }
    });
    
    if(type == 'blank')
    {
      styles = new OpenLayers.StyleMap({
            "default": {
            strokeWidth: 0,
            strokeColor:'#fff',
            fillColor: "#fff",
            fillOpacity: "0" 
            }
        });
    }else if(type === 'thick'){
        styles =  new OpenLayers.StyleMap({
        "default": {
            strokeWidth: 2.5,
            strokeColor:'#000',
            fillColor: "#fff",
            fillOpacity: "0.0",
            strokeDashstyle : "dashdot"
          }
        }); 
    }

return styles
}