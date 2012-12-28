<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Imported DWG File Viewer</title>
        <script type="text/javascript" src="resources/js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="resources/js/ext-base.js"></script>
        <script type="text/javascript" src="resources/js/ext-all.js"></script>
        <script src="openlayers/lib/OpenLayers.js"></script>
        <script type="text/javascript" src="resources/js/GeoExt.js"></script>
        
        
        
        <link rel="stylesheet" type="text/css" href="resources/css/ext-all.css"/>
        <script type="text/javascript">
        //Globals
        var mapPanel, tree,map;


        </script>
        <script type="text/javascript" src="resources/js/toolbar.js"></script>
        <script type="text/javascript" src="tree.js"></script>
    </head>
    <body>
        <div id="desc">
            <?php

                include "LayerListing.php";
            ?>
        </div>
    </body>
</html>
