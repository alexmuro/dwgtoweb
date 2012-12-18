// Define the overlay, derived from google.maps.OverlayView
function Label(opt_options) {
 // Initialization
 this.setValues(opt_options);

 // Label specific
 var span = this.span_ = document.createElement('span');
 span.style.cssText = 'position: relative; left: -50%; top: -1px; ' +
                      'white-space: nowrap; border: 0px solid blue; ' +
                      'padding: 2px; background-color: none';

 var div = this.div_ = document.createElement('div');
 div.appendChild(span);
 div.style.cssText = 'position: absolute; display: none';
};

Label.prototype = new google.maps.OverlayView;

// Implement onAdd
Label.prototype.onAdd = function() {
 var pane = this.getPanes().overlayLayer;
 pane.appendChild(this.div_);

 // Ensures the label is redrawn if the text or position is changed.
 var me = this;
 this.listeners_ = [
   google.maps.event.addListener(this, 'position_changed',
       function() { me.draw(); }),
   google.maps.event.addListener(this, 'text_changed',
       function() { me.draw(); })
   //google.maps.event.addListener(this, 'zoom_changed',
       //function() { me.draw(); })
 ];
};

// Implement onRemove
Label.prototype.onRemove = function() {
 this.div_.parentNode.removeChild(this.div_);

 // Label is removed from the map, stop updating its position/text.
 for (var i = 0, I = this.listeners_.length; i < I; ++i) {
   google.maps.event.removeListener(this.listeners_[i]);
 }
};

// Implement draw
 Label.prototype.draw = function() {
 var projection = this.getProjection();
 var position = projection.fromLatLngToDivPixel(this.get('position'));
 var poly = this.get('polygon').getPath();

 
 var topleft = projection.fromLatLngToDivPixel(poly['b'][0]);
 var topright = projection.fromLatLngToDivPixel(poly['b'][2]);
 var bottomleft = projection.fromLatLngToDivPixel(poly['b'][1]);
 var polywidth = (topright.x - topleft.x);
 var polyheight = (bottomleft.y - topright.y);
 var middle = ((bottomleft.y + topright.y)/2) - (polyheight/4);
 var letterwidth = polywidth / this.get('text').toString().length;
 
  var fontsize = letterwidth;
  if(fontsize > polyheight)
  {
     fontsize = polyheight;
  }
  var topval = position.y;
  if(fontsize > 10)
  {
   topval =  position.y + parseFloat(fontsize/2,10);
  }
  //console.log(this.get('text').toString()+", "+letterwidth+", "+fontsize);
 var div = this.div_;
 var span = this.span_;
 span.style.fontSize = fontsize;

 //style.width = polywidth + 'px';
 //this.span_.style.height = polyheight + 'px';
 div.style.left = position.x + 'px';
 div.style.top = middle + 'px';
 div.style.display = 'block';
 //div.style.border = '1px solid red';

  //console.log("text1"+div.style.width+","+div.style.height); 
  this.span_.innerHTML = this.get('text').toString();


 
 
};
//-------------------------------------
//---Implement Label Visibility methods
//-------------------------------------

Label.prototype.hide = function() {
  if (this.div_) {
    this.div_.style.visibility = "hidden";
  }
}

Label.prototype.show = function() {
  if (this.div_) {
    this.div_.style.visibility = "visible";
  }
}

Label.prototype.toggle = function() {
  if (this.div_) {
    if (this.div_.style.visibility == "hidden") {
      this.show();
    } else {
      this.hide();
    }
  }
}

Label.prototype.isVisible = function() {
  if (this.div_) {
    if (this.div_.style.visibility == "visible") {
      return true;
    }
  }
  return false;
}
