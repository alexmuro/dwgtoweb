
function loadData(layers){
	var output_data = {};
		var layerurl = "../data/read/getLayerGeoJSONmultiple.php";
		$.ajax( {url:layerurl,type:"POST",data:{layers:layers},async:false} )
	    .done(function(data) { 
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

function crop(input_data){
	drop_list = [];
	$.each(input_data.features,function(index,feature){
		drop = true;
		if(typeof feature.geometry != 'undefined'){
			drop = false;
			if(feature.geometry.type == 'LineString'){
				for(var x = 0;x < feature.geometry.coordinates.length;x++){
					if(typeof feature.geometry.coordinates[x] != 'undefined'){
						if(feature.geometry.coordinates[x][0] < minX || feature.geometry.coordinates[x][0] > maxX || feature.geometry.coordinates[x][1] < minY || feature.geometry.coordinates[x][1] > maxY){
							
							drop= true;
						}
						
					}

				}
			}
			if(feature.geometry.type == 'Polygon'){
				for(var x = 0;x < feature.geometry.coordinates[0].length;x++){
					if(typeof feature.geometry.coordinates[0][x] != 'undefined'){

						if(feature.geometry.coordinates[0][x][0] < minX || feature.geometry.coordinates[0][x][0] > maxX || feature.geometry.coordinates[0][x][1] < minY || feature.geometry.coordinates[0][x][1] > maxY){
							drop = true;
						}
						
					}

				}
			}
			if(feature.geometry.type == 'Point'){
			
				if(feature.geometry.coordinates[0] < minX || feature.geometry.coordinates[0] > maxX || feature.geometry.coordinates[1] < minY || feature.geometry.coordinates[1] > maxY){
					drop = true;
				}
			}
		}
		if(drop){
			drop_list.push(index);
		}
	});
	drop_list.forEach(function(feat){
		input_data.features.splice(feat,1);
	})
	return input_data;

}

function scaleGeoJSON(input_data,scale,translateX,translateY){
	input_data.features.forEach(function(feature){
		if(feature.geometry.type == 'GeometryCollection'){
			scaleGeometryCollection(feature.geometry.geometries,scale,translateX,translateY);
		}
		if(typeof feature.geometry != 'undefined'){
			if(feature.geometry.type == 'LineString'){
				for(var x = 0;x < feature.geometry.coordinates.length;x++){
					if(typeof feature.geometry.coordinates[x] != 'undefined'){
						testx =  Math.abs((feature.geometry.coordinates[x][0]+translateX)/scale);
						testy =  Math.abs((feature.geometry.coordinates[x][1]+translateY)/scale);
						feature.geometry.coordinates[x][0] = testx;
						feature.geometry.coordinates[x][1] = testy;
					}

				}
			}
			if(feature.geometry.type == 'Polygon'){
				for(var x = 0;x < feature.geometry.coordinates[0].length;x++){
					if(typeof feature.geometry.coordinates[0][x] != 'undefined'){
						testx =  Math.abs((feature.geometry.coordinates[0][x][0]+translateX)/scale);///scale
						testy =  Math.abs((feature.geometry.coordinates[0][x][1]+translateY)/scale);
						feature.geometry.coordinates[0][x][0] = testx;
						feature.geometry.coordinates[0][x][1] = testy;
					}

				}
			}
			if(feature.geometry.type == 'Point'){
			
				testx =  Math.abs((feature.geometry.coordinates[0]+translateX)/scale);///scale
				testy =  Math.abs((feature.geometry.coordinates[1]+translateY)/scale);
				feature.geometry.coordinates[0] = testx;
				feature.geometry.coordinates[1] = testy;
			}
		}
	});
	return input_data;
}

function scaleGeometryCollection(input_data,scale,translateX,translateY){
	input_data.forEach(function(geometry){
		if(geometry.type == 'GeometryCollection'){
			scaleGeometryCollection(geometry.geometries);
		}
		if(typeof geometry != 'undefined'){
			if(geometry.type == 'LineString'){
				for(var x = 0;x < geometry.coordinates.length;x++){
					if(typeof geometry.coordinates[x] != 'undefined'){
						testx =  Math.abs((geometry.coordinates[x][0]+translateX)/scale);
						testy =  Math.abs((geometry.coordinates[x][1]+translateY)/scale);
						geometry.coordinates[x][0] = testx;
						geometry.coordinates[x][1] = testy;
					}

				}
			}
			if(geometry.type == 'Polygon'){
				for(var x = 0;x < geometry.coordinates[0].length;x++){
					if(typeof geometry.coordinates[0][x] != 'undefined'){
						testx =  Math.abs((geometry.coordinates[0][x][0]+translateX)/scale);///scale
						testy =  Math.abs((geometry.coordinates[0][x][1]+translateY)/scale);
						geometry.coordinates[0][x][0] = testx;
						geometry.coordinates[0][x][1] = testy;
					}

				}
			}
			if(geometry.type == 'Point'){
			
				testx =  Math.abs((geometry.coordinates[0]+translateX)/scale);///scale
				testy =  Math.abs((geometry.coordinates[1]+translateY)/scale);
				geometry.coordinates[0] = testx;
				geometry.coordinates[1] = testy;
			}
		}
	});
	return input_data;
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
			coordinates = features[y].geometry.coordinates;
			for(var x = 0;x < coordinates.length;x++){
				x_coord = coordinates[x][0]
				y_coord = coordinates[x][1]
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
			for(var x = 0;x < coordinates.length;x++){
				x_coord = coordinates[x][0]-lminX;
				y_coord = coordinates[x][1]-lminY;
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

function getBoothNum(fminX,fmaxX,fminY,fmaxY){
	boothnum = "0";
	bn_features = booth_num_data.features;
	for(var y=0;y<bn_features.length;y++){
		if(bn_features[y].geometry.coordinates[0] > fminX && bn_features[y].geometry.coordinates[0] < fmaxX && bn_features[y].geometry.coordinates[1] > fminY && bn_features[y].geometry.coordinates[1] < fmaxY){
			boothnum = bn_features[y].properties['meta'];
			booth_num_data.features.splice(y,1);
			y = bn_features.length;
		}
	}
	//console.log(boothnum,fminX,fmaxX,fminY,fmaxY);
	return boothnum;
}


function findMinMax(input_data){
	features = input_data.features;
	for(var y=0;y<features.length;y++){
		if(typeof features[y].geometry != 'undefined'){
			if(features[y].geometry.type == 'Polygon'){
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
			if(features[y].geometry.type == 'LineString'){
				coordinates = features[y].geometry.coordinates;
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
}



function createMap(event_cycle_id,floor_data){
	var pass_data = {};
	var output;
	pass_data['event_cycle_id'] = event_cycle_id;
	pass_data['floor_data'] = floor_data;
	import_url = '../data/create/createMap.php'
	$.ajax( { url:import_url, async:false, type:"POST", data: { create_map:pass_data } })
    	.done(function(data) { 
    		output = data;
    	})
    	.fail(function() { console.error("loading error"); });
  return output;
}

function sendToImport(input_data,event_cycle_id,map_id){
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
