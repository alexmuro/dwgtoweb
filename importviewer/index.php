<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Imported DWG File Viewer</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="resources/js/ext-base.js"></script>
        <script type="text/javascript" src="resources/js/ext-all.js"></script>
        <script src="openlayers/lib/OpenLayers.js"></script>
        <script type="text/javascript" src="resources/js/GeoExt.js"></script>
        <link rel="stylesheet" type="text/css" href="resources/css/ext-all.css"/>

         

        
        <script type="text/javascript">
        //Globals
        var mapPanel, tree,map;
        </script>
        <!-- Viewer Files -->
        <script type="text/javascript" src='../js/getJson.js'></script>
        <script type="text/javascript" src='resources/js/behavior.js'></script>
        
        <!-- Uploader Files -->


        <!-- Main js file -->
        <script type="text/javascript" src="app.js"></script>
    </head>
    <body>
    <div id="desc">
        <?php
         include 'partials/LayerListing.php';
         
         ?>
    </div>
    </body>
</html>
