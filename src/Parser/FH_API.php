<?php 
namespace App\Parser;

class FH_API
{
    private $key = "";
    private $base_api_url = "https://api.freelancehunt.com/v2/";
    
    private $location;
    private $page = 1;
    private $pages = 1;
    
    private $ch;
    private $result;
    private $counter = 0;
    private $info;
    
    public function __construct(string $key)
    {
        $this->key = $key;
    }
    
    public function go()
    {
        $this->ch = curl_init();
        
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->key));
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_URL, $this->get_url());
        
        $this->result = json_decode(curl_exec($this->ch));    
    
        $this->info = curl_getinfo($this->ch);
        curl_close($this->ch);
        
        $this->update_pages_count();
    }
    
    private function update_pages_count()
    {
        if(isset($this->result->links))
        {
            if($this->result->links->next)
            {
                preg_match('/page\[number\]=\d+/', $this->result->links->last, $matches);
                $page = explode('=', $matches[0]);
                $this->pages = $page[1];
            }
        }
    }
        
    
    public function get_url()
    {
        $url = $this->base_api_url;
        if($this->location) $url.= $this->location;
        if($this->page > 1) $url.= "?page[number]=" . $this->page;
        
        return $url;
    }
    
    public function location($location)
    {
        $this->location = $location;
        return $this;
    }
    
    public function page($page)
    {
        $this->page = $page;
        return $this;
    }        
    
    public function next_page()
    {
        $this->page++;
        return $this;
    }        
     
    public function status()
    {
        return $this->info;
    }
    
    public function result_code()
    {
        return $this->status()['http_code'];
    }
    
    public function reset()
    {
        $this->counter = 0;
        return $this;
    }
    
    public function next()
    {        
        $this->result->data[$this->counter]->attributes->id = $this->result->data[$this->counter]->id;
        $result = $this->result->data[$this->counter]->attributes;
        $this->counter++;                               
        
        if($this->counter == count($this->result->data))
        {
            $this->reset()->next_page()->go();            
        }
        
        if($this->page == $this->pages) return false;
        
        return $result;
    }
    
}