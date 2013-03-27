<?php
$inputfile = $_POST['upload'];

$cmd = "../text2db/AutoCADConverter uploads/".$inputfile." txts/".$inputfile.".txt";
echo exec($cmd);