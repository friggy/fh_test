<?php
set_time_limit(600);
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use App\Router\Router;

require_once "vendor/autoload.php";
require_once "config.php";

define('__ROOT_DIR__', __DIR__);

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

$connectionParams = array(
    'dbname' =>  Config::$db_name,
    'user' => Config::$db_user,
    'password' => Config::$db_pass,
    'host' => Config::$db_host,
    'driver' => 'pdo_mysql',
);

$entityManager = EntityManager::create($connectionParams, $config);

$router = new Router($entityManager);

$router->add_route('/', 'App\Controller\JobsController.show_list');
$router->add_route('/skill_stats', 'App\Controller\JobsController.show_skill_stats');
$router->add_route('/budget_stats', 'App\Controller\JobsController.show_budget_stats');
