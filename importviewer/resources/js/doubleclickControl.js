var DblclickFeature = OpenLayers.Class(OpenLayers.Control, {
  initialize: function (layer, options) {
    OpenLayers.Control.prototype.initialize.apply(this, [ options ]);
    this.handler = new OpenLayers.Handler.Feature(this, layer, {
      dblclick: this.dblclick
    });
  }
});



