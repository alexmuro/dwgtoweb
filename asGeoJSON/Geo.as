package {
  import flash.display.Sprite;
  import flash.text.TextField;
  import flash.text.*;
  import flash.media.*;
	import flash.utils.*;
	import flash.display.*;
	import flash.events.Event;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.events.MouseEvent;
	import flash.net.URLRequestMethod;
	import flash.net.URLLoaderDataFormat;
	import flash.net.URLVariables;
	
	[SWF(backgroundColor="0xffffff", width="2800" , height = "2800")]
  public class Geo extends Sprite {
  var coordsTextField:TextField = new TextField();
 
    public function Geo() {
  	/*
      load_json("expobooths.json","geo");
      load_json("expofloor.json","geo");
			load_json("topo_expo_booths.json","topo");
			load_json("topo_expo_floor.json","topo");
      load_json("expofloor.json","geo");
	*/		
			load_json("surf_outline.topojson","topo");
			//load_json("surf_outline.geojson","geo");

			stage.addEventListener( MouseEvent.MOUSE_MOVE, mouse_listener );
			coordsTextField.x = 300;
			coordsTextField.scaleX = coordsTextField.scaleY = 3;
    }

    function load_json(file_location:String,json_type:String):void
    {
    	var myRequest:URLRequest = new URLRequest(file_location);
			var myLoader = new URLLoader();
			if(json_type == "geo"){
				myLoader.addEventListener(Event.COMPLETE, on_geo_load);
			}
			else if(json_type == "topo"){
				myLoader.addEventListener(Event.COMPLETE, on_topo_load);
			}
			myLoader.load(myRequest);
    }
    
    function mouse_listener(evt:MouseEvent):void
		{
			
			coordsTextField.defaultTextFormat = coordsTextField.getTextFormat();
			coordsTextField.replaceText(0,coordsTextField.length,"x:"+String(mouseX) +" , y:" +String(mouseY));
			addChild(coordsTextField);
		}

		function on_topo_load(event:Event):void
		{
			var jsonContent:URLLoader = URLLoader(event.target);
			var data:Object = JSON.parse(jsonContent.data);

			var display_txt:TextField = new TextField();
			display_txt.autoSize = TextFieldAutoSize.LEFT;
      addChild(display_txt);
      display_txt.appendText(String(data.type));
      display_txt.appendText("\n");
      

      var booth_txt:TextField = new TextField();
			booth_txt.y = 100;
      booth_txt.autoSize = TextFieldAutoSize.LEFT;
			addChild(booth_txt);

			var scale:Number = 4.8;//5;//8;
      var x_offset:int =100;//22500;//200;
      var y_offset:int =70;// 1650;//200;6/
      

      for (var first_obj:String in data.objects) break;
      display_txt.appendText("Num Features: "+String(data.objects[first_obj].geometries.length)+"\n");
			var geometries:Object = data.objects[first_obj].geometries;
			 display_txt.appendText("Geom Length: "+String(geometries.length)+"\n");
			for(var j:int=0; j < geometries.length; j++){
				if(j > 1500){	
					//booth_txt.appendText(String(j)+":"+String(geometries[j].type)+"\n");
				}
				if(geometries[j].type != null && geometries[j].type != 'Point'){
					
        	if(geometries[j].type == "LineString"){
        		var num_coords:int =  geometries[j].arcs.length;
						var object_type:String =  geometries[j].type;
						var arcs:Object = geometries[j].arcs;
        	}
        	else{
						var num_coords:int =  geometries[j].arcs[0].length;
						var object_type:String =  geometries[j].type;
						var arcs:Object = geometries[j].arcs[0];
					}

					var anchorsString:String = "";
					for(var i:int = 0; i < arcs.length;i++){
						if(arcs[i] >= 0){
							var arc:Object = data.arcs[arcs[i]];
	  					var coords:Object = arcToCoordinates(data,arc);
	  				}
	  				else{
	  					var reverse_arc = ((arcs[i]*-1)-1);
	  					var arc:Object = data.arcs[reverse_arc];
	  					var coords:Object = arcToCoordinates(data,arc);
	  					var forward_string:String = ""
	  					coords = coords.reverse();
	  				}
	  				for(var h:int = 0; h < coords.length;h++){
	  					anchorsString += ((coords[h][0]/scale)+x_offset)+","
	  					anchorsString += ((coords[h][1]/scale)+y_offset)+","
	  				}

					}
					anchorsString =  anchorsString.slice( 0, -1 );
					//display_txt.appendText(anchorsString+"\n");
					var anchors:Array = anchorsString.split(',');
					var pathInfo:Object = createDrawPathInfo(anchors);
					var jbooth:Sprite = drawPolygon(pathInfo,0x00FF00,1);

					if(j == 1 || j == 10 || j == 1761){
						booth_txt.appendText(String(j)+":"+String(jbooth.getBounds(stage)+",num coords:"+num_coords+"object type:"+object_type+"\n"));
					}
					addChild(jbooth);
				}
			}
			
		}

		function arcToCoordinates(topology:Object, arc:Object) {
		  var x = 0, y = 0;
		  return arc.map(function(point) {
		    return [
		      (x += point[0]) * topology.transform.scale[0] + topology.transform.translate[0],
		      (y += point[1]) * topology.transform.scale[1] + topology.transform.translate[1]
		    ];
		  });
		}

    function on_geo_load(event:Event):void
		{
			

			var jsonContent:URLLoader = URLLoader(event.target);
			var data:Object = JSON.parse(jsonContent.data);
			var booth_txt:TextField = new TextField();
					booth_txt.y = 100;
        	booth_txt.autoSize = TextFieldAutoSize.LEFT;
					addChild(booth_txt);
	        booth_txt.x=1000;

			var display_txt:TextField = new TextField();
			display_txt.autoSize = TextFieldAutoSize.LEFT;
			display_txt.x = 1000;
      addChild(display_txt);
      display_txt.appendText(String(data.type));
      display_txt.appendText("\n");
      display_txt.appendText("Num Features: "+String(data.features.length)+"\n");
      

      var scale:Number = 5;//5;//8;
      var x_offset:int =-5000;//22500;//200;
      var y_offset:int =00;// 1650;//200;6/

      x_offset += 1000;
      for(var j:int = 0; j < data.features.length;j++ )
      {

      	var num_coords:int = data.features[j].geometry.coordinates[0].length;
      	var object_type:String = data.features[j].geometry.type;
        var anchorsString:String = "";
       
	        for(var i:int = 0; i < num_coords; i++) {
	  				 
	  				anchorsString += String(((data.features[j].geometry.coordinates[0][i][0]/scale)+x_offset))+ ",";
						anchorsString += String(((data.features[j].geometry.coordinates[0][i][1])/scale)+y_offset)+ ",";
					}
					anchorsString =  anchorsString.slice( 0, -1 );
					//display_txt.appendText(anchorsString);
					var anchors:Array = anchorsString.split(',');
					var pathInfo:Object = createDrawPathInfo(anchors);
					var jbooth:Sprite = drawPolygon(pathInfo,0x00FF00,1);

					if(j == 1 || j == 1000 || j == 10000){
						booth_txt.appendText(String(j)+":"+String(jbooth.getBounds(stage)+",num coords:"+num_coords+"object type:"+object_type+"\n"));
					}
					addChild(jbooth);
				
			}
			
		}

  function createDrawPathInfo(anchors:Array):Object{
			var pathCommands:Vector.<int> = new Vector.<int>();
			var pathCoordinates:Vector.<Number> = new Vector.<Number>();
			var totalAnchors:int = anchors.length;
			var halftheAnchors:int = totalAnchors/2;
			var i:int = 0;
			
			pathCommands.push(1);
			for (i = 0; i < totalAnchors;i++)
			{
				if(i>0 && i<halftheAnchors) pathCommands.push(2);  
				pathCoordinates.push((Number(anchors[i])))
			}
			
			return { commands:pathCommands, coordinates:pathCoordinates };
		}

function drawPolygon(path:Object, color:uint, opacity:Number):Sprite{
			var drawableObj:Sprite = new Sprite();
			drawableObj.graphics.lineStyle(1, 0x000000, opacity, false, LineScaleMode.NORMAL, CapsStyle.SQUARE, JointStyle.MITER);
			//drawableObj.graphics.beginFill(color,.5);
			drawableObj.graphics.drawPath(path.commands, path.coordinates);
			//drawableObj.graphics.endFill();
			return drawableObj;
		}
  }
}