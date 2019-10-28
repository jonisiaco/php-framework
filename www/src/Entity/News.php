<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="news", options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 */
class News
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /** 
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="name")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    protected $author;

    /** 
     * @ORM\Column(name="updated_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $updated_at;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created_at;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at = null)
    {
        if ($created_at){
            return $this->created_at = $created_at;
        }
        return $this->created_at = new \DateTime();
    }

}