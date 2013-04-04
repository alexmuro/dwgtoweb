import flash.display.Sprite;

private function createDrawPathInfo(anchors:Array):Object
{
	var pathCommands:Vector.<int> = new Vector.<int>();
	var pathCoordinates:Vector.<Number> = new Vector.<Number>();
	var totalAnchors:int = anchors.length;
	var halftheAnchors:int = totalAnchors/2;
	var i:int = 0;
	
	pathCommands.push(1);
	for (i = 0; i < totalAnchors;i++)
	{
		if(i>0 && i<halftheAnchors) pathCommands.push(2);  
		pathCoordinates.push((Number(anchors[i])));
	}
	
	return { commands:pathCommands, coordinates:pathCoordinates };
	
}

private function drawPolygon(path:Object, color:uint, opacity:Number):Sprite
{
	var drawableObj:Sprite = new Sprite();
	drawableObj.graphics.beginFill(color,opacity);
	drawableObj.graphics.drawPath(path.commands, path.coordinates);
	drawableObj.graphics.endFill();
	return drawableObj;
}


// Usage

var anchorsString:String = "0,0,10,10,20,10,10,0";
var anchors:Array = String(_data.@anchors).split(',');
var pathInfo:Object = createDrawPathInfo(anchors);
var booth:Sprite = drawPolygon(pathInfo);
addChild(booth);

