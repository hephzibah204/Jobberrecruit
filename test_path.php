<?php
require 'demo/app/Config/Paths.php';
$p = new Config\Paths();
echo "writableDirectory from Paths: " . $p->writableDirectory . "\n";
echo "Realpath: " . realpath($p->writableDirectory) . "\n";
