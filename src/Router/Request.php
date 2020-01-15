<?php
namespace App\Router;

class Request 
{
    private $server;
    private $get;
    private $post;
    
    public function __construct()
    {
        $this->init();
    }
    
    private function init()
    {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;                
    }
    
    public function path() : string
    {
        if(!isset($this->server['PATH_INFO'])) return '/';
        return $this->server['PATH_INFO'];
    }
    
    public function get(string $name)
    {
        if(!isset($this->get[$name])) return null;
        return $this->get[$name];
    }
}