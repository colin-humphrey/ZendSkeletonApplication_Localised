<?php
// Get running server domain name
$server = $_SERVER['SERVER_NAME'];

// controller mappings
$defaults = array(
            'controller' => 'Application\Controller\Index',
            'action'     => 'home'
);

// routes
return array(
    'router' => array(
        'routes' => array(
            // home
            // regex
            'root-regex' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => '(?<locale>/[a-z][a-z]{1}-[a-z][a-z]{1})',
                    'defaults' => $defaults,
                    'spec' => '/%locale%',
                ),
            ),
            'root-regex-trailing-slash' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => '(?<locale>/[a-z][a-z]{1}-[a-z][a-z]{1})/',
                    'defaults' => $defaults,
                    'spec' => '/%locale%',
                ),
            ),            
            // literal
            'root' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => $defaults,
                ),
            ),
            // home
            // segment
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/home[/]',
                    'defaults' => $defaults
                ),
            ),
            // regex
            'home-regex' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => '(?<locale>/[a-z][a-z]{1}-[a-z][a-z]{1})/home',
                    'defaults' => $defaults,
                    'spec' => '/%locale%/home',
                ),
            ),
            'home-regex-trailing-slash' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => '(?<locale>/[a-z][a-z]{1}-[a-z][a-z]{1})/home/',
                    'defaults' => $defaults,
                    'spec' => '/%locale%/home',
                ),
            ),           
            
            // Application module config
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    // service manager
    'service_manager' => array(
    'factories' => array(
        'cache' => function () {
            return Zend\Cache\StorageFactory::factory(array(
                'storage' => array(
                    'adapter' => 'Filesystem',
                    'options' => array(
                        'cache_dir' => __DIR__ . '/../../../data/cache',
                        'ttl' => 100
                    ),
                ),
                'plugins' => array(
                    'IgnoreUserAbort' => array(
                        'exitOnAbort' => true
                    ),
                ),
            ));
        },
    ),

//        'aliases' => array(
//            'translator' => 'MvcTranslator',
//        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        ),
    ),  
    'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',            
            'application/index/index' => __DIR__ . '/../view/application/index/home.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/layout'           => __DIR__ . '/../view/layout/error-layout.phtml',
            'footer' => __DIR__ . '/../view/partial/footer.phtml',
            'header' => __DIR__ . '/../view/partial/header.phtml',            
            'meta' => __DIR__ . '/../view/partial/meta.phtml',
            'navigation' => __DIR__ . '/../view/partial/navigation.phtml'        
        ),
        
        // template path stack
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);