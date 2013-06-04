<?php

/**
 * @author Omar Soto <omarpr@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/business_objects"), $isDevMode);

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);

$entityManager = EntityManager::create($conn, $config);

?>