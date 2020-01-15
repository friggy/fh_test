<?php 
require_once '../bootstrap.php';
use App\Parser\FH_API;
use App\Parser\Parser;

$api = new FH_API(Config::$fh_api_key);
$parser = new Parser($entityManager);

$api->location('projects')->go();

while($data = $api->next())
{
    $parser->parse($data);
}