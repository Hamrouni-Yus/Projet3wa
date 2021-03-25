<?php
namespace Router;
use App\Exceptions\NotFoundException;
class Router
{
    public $url;
    public $routes=[];
    
    //fonction de costruction du model
    public function __construct($url)
    
    {
        
        //initialisation de l'URL dans le constructeur et formatage des / avec trim
        $this->url=trim($url,'/');
    
        
    }
    
    //fonction de recuperation des données
    public function get(string $path,string $action)
    
    {
    
        $this->routes['GET'][]=new Route($path, $action);
    
    }
    
    //fonction d'envoie des données
    public function post(string $path,string $action)
    
    {
        $this->routes['POST'][]=new Route($path, $action); 
        
    }
    
    //fonction d'execution de la route
    public function run()
    
    {
    
        foreach($this->routes[$_SERVER['REQUEST_METHOD']]as $route )
        
        {
            
            if($route->matches($this->url))
            
            {   
            
                return $route->execute(); 
            
            }
            
        }
        
        //si on rencontre une exeption
        throw new NotFoundException(" la page demandé est introuvable.");
    
    }
    
}