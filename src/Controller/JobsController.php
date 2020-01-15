<?php
namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Router\Request;
use Doctrine\ORM\Query\ResultSetMapping;

class JobsController
{
    private $em;
    private $request;
    private $twig;    
    private $items_per_page = 10;
    
    public function __construct(EntityManagerInterface $em, Request $request)
    {
        $this->em = $em;
        $this->request = $request;
        
        $loader = new \Twig\Loader\FilesystemLoader(__ROOT_DIR__.'/templates/');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => __ROOT_DIR__.'/templates_c/',
            'charset'=>'utf-8',
            'auto_reload'=>true
        ]);
        header("Content-type: text/html; charset=utf-8");
    }
    
    public function show_list()
    {                                        
        $page = (int)$this->request->get('page');
        if(!$page) $page = 1;
        $offset = ($page-1) * $this->items_per_page;
        if($offset < 0) $offset = 0;
        
        $dql = "SELECT count(j) FROM App\Model\Job j JOIN j.skills s WHERE (s.id = 1 OR s.id = 99)";
        $query = $this->em->createQuery($dql);
        $total_count = $query->getSingleScalarResult();        
        
        if($offset > $total_count) $offset = $total_count - $this->items_per_page;
        
        $dql = "SELECT j, c FROM App\Model\Job j JOIN j.customer c JOIN j.skills s WHERE (s.id = 1 OR s.id = 99)";
        $query = $this->em->createQuery($dql);
        $query->setMaxResults(10);
        $query->setFirstResult($offset);
        $jobs = $query->getResult();
                
        $this->twig->display('jobs_list.twig', 
            [                
                'jobs' => $jobs,
                'pages' => $this->get_paginator($page, $total_count),
                'current_page' => $page
            ]);
    }      
    
    private function get_paginator(int $current_page, int $total_items)
    {
        $total_pages = ceil($total_items / $this->items_per_page);
        $first_page = $current_page - 2;
        if($first_page < 1) $first_page = 1;
        
        $end_page = $current_page + 3;
        if($current_page < 3) $end_page+= 3 - $current_page;
        if($end_page > $total_pages) $end_page = $total_pages;
        
        $pages = [];
        for($i = $first_page; $i< $end_page; $i++)
        {
            $pages[] = $i;            
        }
        
        return $pages;
    }
    
    public function show_skill_stats()
    {                 
        $dql = "SELECT s, COUNT(j.id) AS c FROM App\Model\Skill s JOIN s.jobs j GROUP BY s.id";
        $query = $this->em->createQuery($dql);        
        $stats = $query->getResult();
                
        $this->twig->display('skill_stats.twig', 
            [                
                'stats' => $stats
            ]);         
    }
    
    public function show_budget_stats()
    {
        $pb_api = file_get_contents('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5');
        $pb_api = json_decode($pb_api);
        
        $exchange_rate = [];
        foreach($pb_api as $ex_rate)
        {
            $exchange_rate[$ex_rate->ccy] = $ex_rate->sale; 
        }        
        
        $sql = "SELECT t.rang AS rang, count(*) AS c
            FROM (
                    SELECT CASE
                            WHEN budget BETWEEN 0 AND 500 THEN '0 - 500'
                            WHEN budget BETWEEN 500 AND 1000 THEN '500 - 1000'
                            WHEN budget BETWEEN 1000 AND 5000 THEN '1000 - 5000'
                            WHEN budget BETWEEN 5000 AND 10000 THEN '5000 - 10000'
                            WHEN budget > 10000 THEN '10000+'
                           ELSE 'Без бюджета'
                           END AS rang
                    FROM (                        
                            SELECT CASE
                                    WHEN currency = 'UAH' THEN budget
                                    WHEN currency = 'RUB' THEN budget * :rub_rate
                                   END AS budget            
                			FROM jobs                            
                        ) jobs
                ) t GROUP BY t.rang";
        
        $params = ['rub_rate' => $exchange_rate['RUR']];
        
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute($params);
        $stats = $stmt->fetchAll();                               
        
        $out_stats = [];
        
        foreach($stats as $row)
        {
            $out_stats[$row['rang']] = $row['c']; 
        }
        
        $this->twig->display('budget_stats.twig',
            [   
                'stats' => json_encode($stats)
            ]);         
    }
}