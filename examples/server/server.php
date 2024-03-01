#!/usr/bin/env php
<?php
use Examples\Server\ServerMain;
require __DIR__.'/../../vendor/autoload.php';

$main = ServerMain::init();
$main->run();