<?php
$inputfile = $_POST['upload'];

$cmd = "../text2db/AutoCADConverter".escapeshellarg("uploads/".$inputfile." txts/".$inputfile.".txt");
echo exec($cmd)