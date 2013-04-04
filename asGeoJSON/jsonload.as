import com.adobe.serialization.json.JSON;

var myRequest:URLRequest = new URLRequest("staff.txt");
var myLoader = new URLLoader();
myLoader.addEventListener(Event.COMPLETE, onload);
myLoader.load(myRequest);

function onload(evt:Event):void
{
var myData:Object = JSON.decode(myLoader.data);

trace(myData.staff[0].firstName);
}