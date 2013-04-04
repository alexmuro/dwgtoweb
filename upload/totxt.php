<?php
$inputfile = $_POST['upload'];

$inputfiledir = "'../text2db/uploads/".$inputfile."'";
$outputfiledir ="'../text2db/txts/".$inputfile.".txt'";

$cmd = "../text2db/AutoCADConverter $inputfiledir $outputfiledir ";
echo system($cmd);

$cmd = "ogr2ogr -f \"GeoJSON\" '../text2db/txts/".$inputfile.".geojson' $inputfiledir";
echo $cmd;
echo system($cmd);