<?php

use DI\ContainerBuilder;
use App\Model\ClassLoader;
use Pecee\SimpleRouter\SimpleRouter;

require_once("vendor/autoload.php");
require_once("routes.php");
define("PATH_INDEX", __DIR__);

SimpleRouter::setDefaultNamespace('\App\Controller');
SimpleRouter::setCustomClassLoader(new ClassLoader());
  
SimpleRouter::start();
