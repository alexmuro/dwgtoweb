<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Imported DWG File Viewer</title>
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="../resources/css/ext-all.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/css/blue-table-style.css"/>
        <link rel="stylesheet" type="text/css" href="../resources/css/style.css"/>        
        
        <!-- Javascript -->
        <script type="text/javascript">
        //Globals
        var mapPanel, tree,map;
        </script>
        <!-- ../resources/js/openlayers.min.js production version 
         script src="../resources/js/topoJSON.js"></script>
        -->
        <script src="../resources/js/openlayers/lib/OpenLayers.js"></script>
        <script src="../resources/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="../resources/js/ext-base.js"></script>
        <script type="text/javascript" src="../resources/js/ext-all.js"></script>
        <script type="text/javascript" src="../resources/js/GeoExt.js"></script>
        <script type="text/javascript" src="../resources/js/jquery.tablesorter.min.js"></script>
        
        <!-- Viewer Files -->
        <script type="text/javascript" src='../resources/js/getJson.js'></script>
        
        <!-- Main js file -->
        <script type="text/javascript" src="app.js"></script>
    </head>
    <body>
    <div id="desc">
        <?php
         $map_id = $_GET['mid'];
         include 'partials/controlPanel.php';
         ?>
    </div>
    </body>
</html>
