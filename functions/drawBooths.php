<script src="js/getLegend.js"></script>
<script type="text/javascript">

var infowindow = new google.maps.InfoWindow();
var geoClicked = 0;
var storeColor;
var color = 1;
var colors = ["#f9f4fa","#d3dbeb","#99c6df","#67b5bb","#559d90","#308273","#165d50"];
var colors2 = ['#66CC00','#339900','#666','#FFFF00'];
var masterColor =[colors,colors2];
var borderColor = 0;
var borders = ['#55BB00','#228800','#555','#eeee00'];
var masterBorder = [borders]
var curCounty;
var legendItems = Array('Rented','Reserved','Available','Pending');

function draw_booths(map){
    
    //Clear Current Blocks From the Map and Restore County
    for (var i = 0; i < poly.length; i++ ) {
      poly[i].setMap(null);
    }
    
    var debugging = true;
    var jURL =  'models/getBooths.php?map=<?php echo $mapid;?>';
    if(debugging) 
      console.log(jURL);
    
    $.ajax({
      url: jURL,
      async:false,
      dataType: "json",
      beforeSend: function(x) {
        
      },
      success: function(data){
        //console.log("Sucess");
        //console.log(data);
        var dataLength = data.length;
        
        
         console.log('start loop')
         for (var x in data)
          {
           bgCoords = [];
            
              //console.log(data[x]['boothnum'])
              //console.log(data[x]['x'] + "," + data[x]['y']);
              //console.log(data[x]['w'] + "," + data[x]['h']);
              x1 = parseFloat(data[x]['x'])/100;
              y1 = parseFloat(data[x]['y'])/100;
              x2 = parseFloat(data[x]['x'])/100 + (parseFloat(data[x]['w'])/100);
              y2 = parseFloat(data[x]['y'])/100 + parseFloat(data[x]['h'])/100;
              centerx = x1 + ((x2 - x1) / 2);
              centery = y1 + ((y2 - y1) / 2);
              //console.log(x1 + "," + x2);
              //console.log(y1 + "," + y2);
              center = new google.maps.LatLng(centerx,centery);

              bgCoords.push(new google.maps.LatLng(x1,y1));
              bgCoords.push(new google.maps.LatLng(x1,y2));
              bgCoords.push(new google.maps.LatLng(x2,y2));
              bgCoords.push(new google.maps.LatLng(x2,y1));
            
            //----------------------
            //--color customization 
            //--
            //----------------------            
            
            //---sales view---------
            var colorIndex = 2;
            
            if(data[x]['availability'] == 'rented')
              {
                colorIndex = 0;
              }
            else if(data[x]['availability'] == 'reserved')
              {
                colorIndex = 1;
              }
            else if(data[x]['availability'] == 'available')
              {
                colorIndex = 2;
              }
            else if(data[x]['availability'] == 'pending')
              {
                colorIndex = 3;
              }
            //----------------------

            poly.push(
              new google.maps.Polygon
              ({
                paths: bgCoords, 
                strokeColor: masterBorder[borderColor][colorIndex],
                strokeOpacity: 0.8,
                strokeWeight: 1,
                fillColor: masterColor[color][colorIndex],
                fillOpacity: 0.5
              })
            );
            boothnum = data[x]['boothnum'];
            curbooth = {};
            curbooth['avail'] = data[x]['availability'];
            curbooth['style'] = data[x]['style'];
            boothdata[data[x]['boothnum']] = curbooth;
          
          
            /*
            labels.push(
              new Label
              ({
                map: map,
                text: data[x]['boothnum'],
                position: center,
                polygon:poly[poly.length-1]
              })
            );
            */
            //labels[labels.length-1].hide();
            //label.hide();
           
            
            
            //console.log(label.getBounds())

            poly[poly.length-1].setMap(map);

            poly[poly.length-1].description =data[x]['boothnum'];
            poly[poly.length-1].name = data[x]['boothnum'];
            
            //---Mouse Over Event-------------
            google.maps.event.addListener(poly[poly.length-1],"mouseover", function(event) {
              
              storeColor = this.fillColor;
              document.getElementById("names").innerHTML='<b>Booth Number:</b>'+this.name+'<br><b>Availability:</b> '+boothdata[this.name]['avail']+'<br><b>Style:</b> '+boothdata[this.name]['style'];
              this.setOptions({fillColor: "#ccccff"});
            
            });
            //---Mouse Out Event------------------
            google.maps.event.addListener(poly[poly.length-1],"mouseout", function(event) {
            
              //document.getElementById("names").innerHTML='';
              this.setOptions({fillColor: storeColor});
            
            });
        }//end json data loop

      },//success
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("XMLHttpRequest="+XMLHttpRequest.responseText+"\ntextStatus="+textStatus+"\nerrorThrown="+errorThrown);
      }
    });
  document.getElementById("legend").innerHTML= getLegend('Legend',legendItems,masterColor[color],masterBorder[borderColor]);
}



</script>