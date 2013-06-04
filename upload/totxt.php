<?php
$inputfile = $_POST['upload'];
$inputfiledir = "'../text2db/uploads/".$inputfile."'";
$outputfiledir ="'../text2db/txts/".preg_replace('/\s+/', '', $inputfile).".geojson'";
$cmd ="ogr2ogr -f \"GeoJSON\" $outputfiledir $inputfiledir -dim 2";

exec($cmd,$output,$return);
if(!$return){
	echo $outputfiledir;
}
else
{
	print_r($output);

}