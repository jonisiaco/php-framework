<?php

# php vendor/bin/doctrine orm:schema-tool:update --dump-sql //Validate Entity
# php vendor/bin/doctrine orm:schema-tool:update --force //Schema Sync
# php vendor/bin/doctrine orm:generate-entities ./src/Entity/ --generate-annotations=true //Generating Entity Getters and Setters

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 */
class User
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="name", type="string", length=100, nullable=false) 
     */
    protected $name;

    /** 
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    protected $email;

    /** 
     * @ORM\Column(name="password", type="blob") 
     */
    protected $password;

    /** 
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"}) 
     */
    protected $created_at;

    /*
    public function __construct()
    {
        $this->created_at = new DateTime(); 
    }
    */

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

}