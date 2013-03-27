<?php
$inputfile = $_POST['upload'];
echo $inputfile;

$cmd = "../text2db/AutoCADConverter '../text2db/uploads/".$inputfile."' '../text2db/txts/".$inputfile.".txt'";
echo $cmd;
echo system($cmd);