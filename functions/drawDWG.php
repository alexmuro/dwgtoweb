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

function draw_dwg(map,id,inputcolor){
    
    //Clear Current Blocks From the Map and Restore County
    /*
    for (var i = 0; i < poly.length; i++ ) {
      poly[i].setMap(null);
    }*/
    
    var debugging = true;
    var jURL =  'models/getDWG.php?map='+id;
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
        //console.log(data);
        
         console.log('start loop')
         for (var x in data)
          {
           geo = jQuery.parseJSON( data[x]['geo'] );
           
           bgCoords = [];
           for(var y in geo)
           {
            
              x1 = (parseFloat(geo[y]['x'])/1000);
              y1 = (parseFloat(geo[y]['y'])/1000);
              //console.log(y1 + "," + x1);
              /*
               var marker = new google.maps.Marker({
                position: new google.maps.LatLng(y1,x1),
                draggable: false,
                map: map
              });
              */
              bgCoords.push(new google.maps.LatLng(y1,x1,true));
            }
            //console.log(bgCoords);
            //----------------------
            //--color customization 
            //--
            //----------------------            
            
          
            //----------------------
            //console.log(data[x]['id'] +" "+ bgCoords);
            
            poly.push(
              new google.maps.Polygon
              ({
                paths: bgCoords, 
                strokeColor: inputcolor,
                strokeOpacity: 0.8,
                strokeWeight: .5,
                fillColor: inputcolor,
                fillOpacity: 0.5
              })
            );
            
          
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
           

            poly[poly.length-1].setMap(map);
            poly[poly.length-1].description =data[x]['id'];
            poly[poly.length-1].name = data[x]['id'];
            
           //---Mouse Over Event-------------
            google.maps.event.addListener(poly[poly.length-1],"mouseover", function(event) {
              
              storeColor = this.fillColor;
              document.getElementById("names").innerHTML='<b>Booth Number:</b>'+this.name+'</b><br>';
              console.log(this.name);
              var p = this.getPath();
              console.log(p['b'][0]+ p['b'][1]+ p['b'][2]+ p['b'][3]);
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
  //document.getElementById("legend").innerHTML= getLegend('Legend',legendItems,masterColor[color],masterBorder[borderColor]);
}



</script>