<?php
namespace App\Parser;
use Doctrine\ORM\EntityManagerInterface;
use App\Job;
use App\Skill;
use App\Customer;

class Parser
{           
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function parse(object $data) : void
    {
        if($this->job_exists($data->id)) return;
        
        $job = $this->get_job($data);                
        
        if(!$data->is_only_for_plus)
        {
            $customer = $this->get_customer($data->employer);
            $job->setCustomer($customer);
        }
                    
        foreach($data->skills as $skill_row)
        {
            $skill = $this->get_skill($skill_row);            
            $job->addSkill($skill);
        }
        
        $this->em->flush();
    }
    
    private function get_customer(object $data) : Customer
    {
        $customer = $this->em->find('App\Model\Customer', $data->id);
        if($customer) return $customer;
        
        $customer = new Customer();
        $customer->setId($data->id);
        $customer->setLogin($data->login);
        
        $this->em->persist($customer);
        $this->em->flush();
                
        return $customer;
    }
    
    private function job_exists(int $id) : bool 
    {
        $job = $this->em->find('App\Model\Job', $id);
        if($job) return true;
        
        return false;
    }
    
    private function get_job(object $data) : Job
    {
        $job = $this->em->find('App\Model\Job', $data->id);
        if($job) return $job;
        
        $job = new Job();
        $job->setId($data->id);
        $job->setName($data->name);
        $job->setDescription($data->description);
        $job->setStatus($data->status->id);
        
        if($data->budget->amount)
        $job->setBudget($data->budget->amount);
        
        if($data->budget->currency)
        $job->setCurrency($data->budget->currency);
        
        $job->setCreatedTime(time());
        
        $this->em->persist($job);
        $this->em->flush();
                
        return $job;
    }
    
    private function get_skill(object $data) : Skill
    {
        $skill = $this->em->find('App\Model\Skill', $data->id);
        
        if($skill) return $skill;
        
        $skill = new Skill();
        $skill->setId($data->id);
        $skill->setName($data->name);
        
        $this->em->persist($skill);
        $this->em->flush();        
        
        return $skill;
    }
}