
function loadData(layers){
	var output_data = {};
	for(var i =0;i<layers.length;i++){
		var layerurl = "../models/getLayerGeoJSON.php?layer="+String(layers[i]);
		$.ajax( {url:layerurl,async:false} )
	    .done(function(data) { 
	    	output_data[i] = JSON.parse(data);
	    })
	    .fail(function() { console.error("loading error"); });
	}
	return output_data;
}

function findMinMax(input_data){
	for(index in input_data){
		features = input_data[index].features;
		for(var y=0;y<features.length;y++){
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

function createBooths(input_data){
	var booths = [];
	for(index in input_data){
		features = input_data[index].features;
		for(var y=0;y<features.length;y++){
			var lmaxX = undefined;
			var lminX = undefined;
			var lmaxY = undefined;
			var lminY = undefined;
			coordinates = features[y].geometry.coordinates[0];
			for(var x = 0;x < coordinates.length;x++){
				x_coord = Math.abs(coordinates[x][0]+translateX)/scale;
				y_coord = Math.abs(coordinates[x][1]+translateY)/scale;
				//console.log;
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
				x_coord = (Math.abs(coordinates[x][0]+translateX)/scale)-lminX;
				y_coord = (Math.abs(coordinates[x][1]+translateY)/scale)-lminY;
				outer_points += x_coord+','+y_coord+',';
			}
			outer_points = outer_points.slice(0,-1);
			booths[y] = {}
			booths[y]['x']=lminX;
			booths[y]['y']=lminY;
			booths[y]['w']=lmaxX-lminX;
			booths[y]['h']=lmaxY-lminY;
			booths[y]['outer_points']=outer_points;
			//sconsole.log('booth['+y+'] x:'+lminX+' y:'+lminY+' w:'+(lmaxX-lminX)+' h:'+(lmaxY-lminY));
			//console.log('outer_points:'+outer_points);
		}	 
	}
	return booths;
}

function sendToImport(input_data,event_cycle_id,map_id){
	import_url = 'https://admin.ems.lvh.me:3000/ma/event_cycles/'+event_cycle_id+'/import_map'
	for(a=0; a < input_data.length;){
		var pass_data = []
		var loop;
		if(a+200 > input_data.length){
			loop = input_data.length-a;
		}
		else{
			loop = 200;
		}
		for(b = 0; b < loop; b++){
				pass_data[b] = input_data[a];	
				//console.log(b+' '+a);
				//console.log(pass_data[b]['x']+' '+pass_data[b]['y']+' | '+input_data[a]['x']+' '+input_data[a]['y']);
				a++;
		}
		writeToRails(pass_data,map_id);
    	
	 }
	 console.log('success!');
}

function writeToRails(input_data,map_id){
		console.log(input_data[0]['x']+' '+ input_data[0]['y']);
		console.log(input_data[20]['x']+' '+ input_data[0]['y']);
		console.log('*****************************************************');
		$.ajax( { url:import_url, async:false, type:"POST", data: { booths:input_data, map_id:map_id } })
    	.done(function(data) {
    		console.log(data);
    	})
    	.fail(function() { console.error("loading error"); });
}

function createMap(event_cycle_id){
	var pass_data = {};
	var output;
	pass_data['event_cycle_id'] = event_cycle_id;
	import_url = 'https://admin.ems.lvh.me:3000/ma/event_cycles/'+event_cycle_id+'/import_map'
	$.ajax( { url:import_url, async:false, type:"POST", data: { create_map:pass_data } })
    	.done(function(data) { 
    		console.log(data);
    		output = data;
    	})
    	.fail(function() { console.error("loading error"); });
  return output;
}

function testImport(input_data,event_cycle_id,map_id){
	import_url = 'https://admin.ems.lvh.me:3000/ma/event_cycles/'+event_cycle_id+'/import_map'

	var pass_data = [];
		for(b = 0; b < 10; b++){
				pass_data[b] = input_data[b];	
		}

		$.ajax( { url:import_url, async:false, type:"POST", data: { booths:pass_data , map_id:map_id} })
    	.done(function(data) { 
    		console.log(data);
    	})
    	.fail(function() { console.error("loading error"); });
	 
	 console.log('success!');
}