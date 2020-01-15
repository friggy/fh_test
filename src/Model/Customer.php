<?php
namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="customers")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")    
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $login;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getLogin()
    {
        return $this->login;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setLogin($login)
    {
        $this->login = $login;
    }
}