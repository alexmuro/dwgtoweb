var http = require('http');
var util = require('util');
var qs = require('querystring');
var topojson = require("topojson");

http.createServer(function (req, res) {

    console.log('Request received: ');
    var body = '';
    req.on('data', function (chunk) {
        body +=chunk;

    });
    req.on('end', function () {
        var POST = qs.parse(body);
        var geoJSON = JSON.parse(POST.layers);
    
        var newGeo = {}
        newGeo['type'] = 'FeatureCollection';
        newGeo['features'] = geoJSON.features;
        var topo = topojson.topology({geo:geoJSON},{quantization:1e6,verbose:true});
        res.setHeader('Access-Control-Allow-Origin', "*");
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.write(JSON.stringify(topo));
        res.end('');
        console.log('success');
    });
}).listen(7090);
console.log('Server running on port 7090');