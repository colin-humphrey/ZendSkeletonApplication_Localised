<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    // Default locale
    public $defaultLocale = 'en-gb';

    // Locales
    public $locales = array(
        'en-gb' => 1,
        'fr-fr' => 1
    );

    // Phases array
    public $phases   = array(4);

    // Json array
    public $json     = array();

    // home action
    public function homeAction()
    {
        $this->localise('/');
        
        $locale = $this->currentLocale();        

        $this->JSONDataModelFiles($locale, array('footer','header','meta','navigation'));

        $viewVars =  array (
                     'json'=>$this->json,
                     'phases'=>$this->phases,
                     'action'=>$this->getAction(),
                     'locale'=>$locale,
                     'locales'=>$this->locales
                  );        
        
        $this->applyViewVars($viewVars);
        
        $this->applyViewModel(array('locale' => $locale, 'json' => $this->json, 'phases' => $this->phases));
    }

    // return json object from file
    public static function returnJsonFromFile($locale,$filename)
    {
        $json_path    = getenv('APPLICATION_PATH') . 'assets/json/'.$locale.'/'.$filename;
        $is_json_file = is_file($json_path);

        if(!$is_json_file)
        {
          return false;
        }

        $json_open    = fopen($json_path,"r");
        $json_content = fread($json_open, filesize($json_path));
        $json_decoded = json_decode($json_content,true);
        $json         = $json_decoded['fields'];

        fclose($json_open);
        return $json;
    }

    // gets locale
    private function getLocale()
    {
        $route   = $this->getEvent()->getRouteMatch();
        $locale  = $route->getParam('locale');
        if ($route && $locale!==NULL)
        {
            $localParam = $locale;
            $locale = ltrim(($localParam===NULL)? $this->defaultLocale : $localParam,'/');
            return $locale;
        }
        return false;

    }

    // returns controller action
    private function getAction()
    {
        $route   = $this->getEvent()->getRouteMatch();
        $action  = $route->getParam('action');
        if ($action && $action!==NULL)
        {
            return $action;
        }
        return false;
    }

    // returns current route
    private function getRoute()
    {
        $route = $this->getEvent()->getRouteMatch();
        if ($route)
        {
            return $route->getMatchedRouteName();

        }
        return false;
    }

    // redirects user to content matching locale
    private function localise($action)
    {
        $accept_language          = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $localeFromZendRoute      = $this->getLocale();
        $firstLocaleMatched = explode(',',$accept_language);
        $localeFromAcceptLanguage = strtolower($firstLocaleMatched[0]);
        $locale                   = ($localeFromZendRoute===false)?$localeFromAcceptLanguage:$localeFromZendRoute;

        if(!preg_match('/-/',$locale))
        {
            $locale = $locale . '-' . $locale;
        }

        $localeInvalid = !in_array($locale, array_keys($this->locales));
        $localeOff    = $this->locales[$locale]!==1;

        if($localeFromZendRoute === false || $localeInvalid|| $localeOff){

            if($localeInvalid || $localeOff){
                $locale = $this->defaultLocale;
            }

            header('location:'. $locale . $action);
            exit;
        }
    }

    public function JSONDataModelFiles($locale,$files = array())
    {
        foreach($files as $jsonFile)
        {
            $this->json[$jsonFile]  = self::returnJsonFromFile($locale,'m.'.$jsonFile.'.json');              
        }
    }   
    
    public function currentConfig()
    {
        return $this->getServiceLocator()->get('config');        
    }
    
    public function currentLocale()
    {
        return (!in_array($this->getLocale(), 
                array_keys($this->locales)))? $this->defaultLocale: $this->getLocale();
    }
    
    public function applyViewModel($model = array())
    {
        return new ViewModel($model);        
    }
    
    public function applyViewVars($vars = array())
    {
        $this->layout()->setVariables($vars);              
    }
}