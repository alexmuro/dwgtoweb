<?php
$inputfile = $_POST['upload'];
echo $inputfile;

$cmd = "../text2db/AutoCADConverter uploads/".$inputfile." txts/".$inputfile.".txt";
echo $cmd;
echo exec($cmd);