<?php
$inputfile = $_POST['upload'];


$inputfiledir = "'../text2db/uploads/".$inputfile."'";
$outputfiledir ="'../text2db/txts/".preg_replace('/\s+/', '', $inputfile).".txt'";

$cmd = "../text2db/AutoCADConverter $inputfiledir $outputfiledir ";
exec($cmd,$output,$return);
//print_r($output);
if(!$return){
	echo $outputfiledir;
}
else
{
	print_r($output);
}
$cmd = "ogr2ogr -f \"GeoJSON\" '../text2db/txts/".$inputfile.".geojson' $inputfiledir";

