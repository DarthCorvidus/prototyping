#!/usr/bin/env php
<?php
use Examples\Server\ClientMain;
require __DIR__.'/../../vendor/autoload.php';

$main = new ClientMain();
$main->run();