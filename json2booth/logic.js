
function loadData(layers){
	var output_data = {};
		var layerurl = "../data/read/getLayerGeoJSONmultiple.php";
		$.ajax( {url:layerurl,type:"POST",data:{layers:layers},async:false} )
	    .done(function(data) { 
	    	//console.log(data);
	    	output_data = JSON.parse(data);
	    })
	    .fail(function(data) { console.log(data); });
	
	return output_data;
}

function geo2topo(geolayers){
	var output_data = {};
		var layerurl = "http://127.0.0.1:7090";
		var jstring = JSON.stringify(geolayers);
		$.ajax( {url:layerurl,type:"POST",data:{layers:jstring},async:false} )
	    .done(function(data) { 
	    	//console.log(JSON.parse(data))
	    	output_data = data;
	    })
	    .fail(function(data) { console.log(data); });
	return output_data;
}



function scaleFloor(input_data,scale,translateX,translateY){
	console.log('scale:'+scale);
	wierd_name = input_data;
	for(var y=0;y<input_data.features.length;y++){
		if(typeof input_data.features[y].geometry != 'undefined'){

			for(var x = 0;x < input_data.features[y].geometry.coordinates.length;x++){
				if(typeof input_data.features[y].geometry.coordinates[x] != 'undefined'){
					console.log("x:"+x+"y:"+y);
					testx =  Math.abs((input_data.features[y].geometry.coordinates[x][0]+translateX)/scale) || 0;
					testy =  Math.abs((input_data.features[y].geometry.coordinates[x][1]+translateY)/scale) || 0;
					wierd_name.features[y].geometry.coordinates[x][0] = testx;
					wierd_name.features[y].geometry.coordinates[x][1] = testy;
					console.log('output:'+wierd_name.features[y].geometry.coordinates[x][0]);
				}

			}
		}
	}
	
	return wierd_name;
}

function geo2Booths(input_data){
	var booths = [];
	features = input_data.features;
	for(var y=0;y<features.length;y++){
		var lmaxX = undefined;
		var lminX = undefined;
		var lmaxY = undefined;
		var lminY = undefined;
		if(typeof features[y].geometry != 'undefined'){
			coordinates = features[y].geometry.coordinates[0];
			for(var x = 0;x < coordinates.length;x++){
				x_coord = Math.abs(coordinates[x][0]/scale+translateX);
				y_coord = Math.abs(coordinates[x][1]/scale+translateY)
				if(typeof lmaxX == 'undefined' || x_coord > lmaxX){
					lmaxX = x_coord;
				}
				if(typeof lminX == 'undefined' || x_coord < lminX){
					lminX = x_coord;
				}
				if(typeof lmaxY == 'undefined' || y_coord > lmaxY){
					lmaxY = y_coord;
				}
				if(typeof lminY == 'undefined' || y_coord < lminY){
					lminY = y_coord;
				}	
			}	
			var outer_points = '';
			for(var x = 0;x < coordinates.length-1;x++){
				x_coord = (Math.abs(coordinates[x][0])+translateX/scale)-lminX;
				y_coord = (Math.abs(coordinates[x][1])+translateY/scale)-lminY;
				outer_points += x_coord+','+y_coord+',';
			}
		}	
		outer_points = outer_points.slice(0,-1);
		booths[y] = {}
		booths[y]['x']=lminX;
		booths[y]['y']=lminY;
		booths[y]['w']=lmaxX-lminX;
		booths[y]['h']=lmaxY-lminY;
		booths[y]['outer_points']=outer_points;
		booths[y]['num'] = getBoothNum(lminX,lmaxX,lminY,lmaxY);
	}	 
	return booths;
}

function getBoothNum(minX,maxX,minY,maxY){
	return "01";
}


function findMinMax(input_data){
	features = input_data.features;
	for(var y=0;y<features.length;y++){
		//console.log(features[y]);
		if(typeof features[y].geometry != 'undefined'){
			coordinates = features[y].geometry.coordinates[0];
			for(var x = 0;x < coordinates.length;x++){
				x_coord = coordinates[x][0];
				y_coord = coordinates[x][1];
				if(typeof maxX == 'undefined' || x_coord > maxX){
					maxX = x_coord;
				}
				if(typeof minX == 'undefined' || x_coord < minX){
					minX = x_coord;
				}
				if(typeof maxY == 'undefined' || y_coord > maxY){
					maxY = y_coord;
				}
				if(typeof minY == 'undefined' || y_coord < minY){
					minY = y_coord;
				}
			}
		}
	}
}



function createMap(event_cycle_id,floor_data){
	var pass_data = {};
	var output;
	pass_data['event_cycle_id'] = event_cycle_id;
	pass_data['floor_data'] = floor_data;
	import_url = '../data/create/createMap.php'
	$.ajax( { url:import_url, async:false, type:"POST", data: { create_map:pass_data } })
    	.done(function(data) { 
    		//console.log(data);
    		output = data;
    	})
    	.fail(function() { console.error("loading error"); });
  return output;
}

function sendToImport(input_data,event_cycle_id,map_id){
	import_url = ''
	for(a=0; a < input_data.length;){
		var pass_data = []
		var loop;
		if(a+100 > input_data.length){
			loop = input_data.length-a;
		}
		else{
			loop = 100;
		}
		for(b = 0; b < loop; b++){
				pass_data[b] = input_data[a];
				a++;
		}
		writeToDB(pass_data,map_id);
    	
	 }
	 console.log('success!');
}

function writeToDB(input_data,map_id){
		//console.log('*****************************************************');
		import_url = '../data/create/createBooths.php'
		$.ajax( { url:import_url, async:false, type:"POST", data: { booths:input_data, map_id:map_id } })
    	.done(function(data) {
    		//console.log(data);
    	})
    	.fail(function() { console.error("loading error"); });
}
