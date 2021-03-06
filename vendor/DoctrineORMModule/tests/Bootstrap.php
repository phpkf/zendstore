<?php
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfiguration;

chdir(__DIR__);

$previousDir = '.';

while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());

    if ($previousDir === $dir) {
        throw new RuntimeException(
            'Unable to locate "config/application.config.php":'
                . ' is OcraDiCompiler in a sub-directory of your application skeleton?'
        );
    }

    $previousDir = $dir;
    chdir($dir);
}

if  (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

if (!$configuration = @include __DIR__ . '/TestConfiguration.php') {
    $configuration = require __DIR__ . '/TestConfiguration.php.dist';
}

// $configuration is loaded from TestConfiguration.php (or .dist)
$serviceManager = new ServiceManager(new ServiceManagerConfiguration(
    isset($configuration['service_manager']) ? $configuration['service_manager'] : array()
));
$serviceManager->setService('ApplicationConfiguration', $configuration);

/** @var $moduleManager \Zend\ModuleManager\ModuleManager */
$moduleManager = $serviceManager->get('ModuleManager');
$moduleManager->loadModules();
$serviceManager->setAllowOverride(true);

$config = $serviceManager->get('Configuration');
$config['doctrine']['driver']['test'] = array(
    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
    'cache' => 'array',
    'paths' => array(
        __DIR__ . '/DoctrineORMModuleTest/Assets/Entity'
    )
);
$config['doctrine']['driver']['orm_default']['drivers']['DoctrineORMModuleTest\Assets\Entity'] = 'test';
$config['doctrine']['connection']['orm_default'] = array(
    'configuration' => 'orm_default',
    'eventmanager'  => 'orm_default',
    'driverClass'   => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',

    'params' => array(
        'memory'        => true
    )
);

$serviceManager->setService('Configuration', $config);

\DoctrineORMModuleTest\Framework\TestCase::setServiceManager($serviceManager);