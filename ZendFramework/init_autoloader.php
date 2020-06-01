<?php
putenv('APPLICATION_PATH='.__DIR__.'/');
putenv('ZF2_PATH='.__DIR__.'/vendor/zendframework/library');

$zf2Path  = false;
$zf2Error = "Cannot load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.";

if (class_exists('Zend\Loader\AutoloaderFactory'))
{
    return;
}

if (getenv('ZF2_PATH')) 
{
    $zf2Path = getenv('ZF2_PATH');
} 
else 
{
    throw new RuntimeException($zf2Error);
}

if ($zf2Path) 
{
    include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
    Zend\Loader\AutoloaderFactory::factory(array(
        'Zend\Loader\StandardAutoloader' => array(
            'autoregister_zf' => true
        )
    ));
}

if (!class_exists('Zend\Loader\AutoloaderFactory'))
{
    throw new RuntimeException($zf2Error);
}