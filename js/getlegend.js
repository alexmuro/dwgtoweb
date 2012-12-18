function getLegend(title,items,colors,borders)
{
 //console.log('getLegend');
 var output = "<div style='padding-left:10px;'><h3>"+title+ "</h3><table >";
 for(x = 0;x < items.length;x++)
 {
  output += "<tr><td> <div style='width:20px;height:15px;border:2px solid "+borders[x]+";background-color:"+colors[x]+";'></div><td><td> "+items[x]+"</td></tr>";
 }
  output += "</table>";
  output += "</div>";
  return output
}