<?php 
namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 */
class Job
{
    public function __construct() {
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
    }        
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")     
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $budget;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $currency;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $customer_id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $status;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $created_time;
    
    /**     
     * @ManyToMany(targetEntity="Skill", inversedBy="jobs")
     * @JoinTable(name="job_skills",
     *      joinColumns={@JoinColumn(name="job_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="skill_id", referencedColumnName="id")}
     *      )
     */
    private $skills;
    
    /**
     * @ManyToOne(targetEntity="Customer")
     * @JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }
    
    public function addSkill(Skill $skill)
    {
        $this->skills[] = $skill;
    }
    
    public function getCustomer()
    {
        return $this->customer;
    }
    
    public function getSkills()
    {
        return $this->skills;
    }
    
    public function getId()
    {
        return $this->id;   
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getBudget()
    {
        return $this->budget;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
    
    public function getCustomerId()
    {
        return $this->customer_id;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getCreatedTime()
    {
        return $this->created_time;
    }
    
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function setBudget($budget)
    {
        $this->budget = $budget;
    }
    
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function setCreatedTime($created_time)
    {
        $this->created_time = $created_time;
    }
    
}
