<?php
namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity
 * @ORM\Table(name="skills")
 */
class Skill
{
    public function __construct() {
        $this->jobs = new \Doctrine\Common\Collections\ArrayCollection();
    }        

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")     
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ManyToMany(targetEntity="Job", mappedBy="skills")
     */
    private $jobs;
    
    public function getJobs()
    {
        return $this->jobs;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
}