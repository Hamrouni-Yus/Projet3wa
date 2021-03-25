<?php

namespace Router;
use Database\DBConnection;

class Route

{
    
    public $path;
    public $action;
    public $matches; 
    
    //fonction de costruction du model
    public function __construct($path , $action)
        
        {
            $this->path=trim($path, '/');
            $this->action=$action;
        
        }
    
    //fonction de verification de URL passé
    public function matches(string $url)
        
        {
        
            $path=preg_replace('#:([\w]+)#','([^/]+)',$this->path);
            $pathToMatch="#^$path$#";
             
            if(preg_match($pathToMatch,$url,$matches)){
            
                $this->matches=$matches;
                return true;
            
            } else {
            
                return false;
            
            }
        }
         
    //fonction d'execution d'une route
    public function execute()
        
        {
            
            //segmentation de la route en a partir du caractere @
            $params=explode('@',$this->action);
            
            //passage des parametres de connection a la BDD
            $controller=new $params[0](new DBConnection(DB_NAME,DB_HOST,DB_USER,DB_PWD));
            
            //passage de la fonction souhaité de la classe modal
            $method=$params[1];
            
        return isset($this->matches[1])?$controller->$method($this->matches[1]):$controller->$method();
        
        }
      
}