<?php
namespace App\Router;

use Doctrine\ORM\EntityManagerInterface;

class Router
{
    private $em;
    private $request;    
    private $routes = [];
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->request = new Request();
    }
    
    public function add_route(string $path, string $controller_method)
    {
        $path = rtrim($path, '/');
        $this->routes[$path] = $controller_method;
    }
    
    public function route() : void
    {
        $route = $this->get_current_route();                
        
        if(!$route) 
        {
            header('HTTP/1.1 404 Not Found'); 
            header('Status: 404 Not Found');
            exit();
        }
        
        $ref = new \ReflectionClass($route['class_name']);
        $new = $ref->newInstanceArgs(array($this->em, $this->request));
        
        $method = $route['method'];
        $new->$method();
    }
    
    private function get_current_route()
    {
        $current_path = rtrim($this->request->path(),'/');
        if(!isset($this->routes[$current_path])) return null;
        
        $route = $this->routes[$current_path];
        $data = explode('.', $route);
        $out = array(
            'class_name' => $data[0],
            'method' => $data[1],
        );
        return $out;
    }
}

