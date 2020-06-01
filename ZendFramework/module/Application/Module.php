<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Controller\IndexController;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $eventManager->attach('render', array($this, 'setLayoutTitle'));
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, 
            function(MvcEvent $e) 
            {            
                return true;
            }
, -200);     
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function setLayoutTitle($e)
    {
        $defaultLocale        = 'en-gb';

        $matches              = $e->getRouteMatch();
 
       // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');

        // Getting the headTitle helper from the view helper manager
        $headTitleHelper   = $viewHelperManager->get('headTitle');
        
        $siteName   = 'zend2-localised';
            
        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' - ');

        $headTitleHelper->append($siteName);
            
        if ($matches)
        {
            $localeMatched        = $matches->getParam('locale');

            $action               = str_replace('-',' ',$matches->getParam('action'));

            $locale     = (!$localeMatched)? $defaultLocale : $localeMatched;


            // Setting the action, controller, module and site name as title segments
            $headTitleHelper->append(ucwords($action));
            $headTitleHelper->append(ucwords(ltrim($locale,'/')));
        }
        else 
        {
            $headTitleHelper->append('404, status not found');
        }
    }
}
