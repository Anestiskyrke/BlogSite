<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]

class BlogPost
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private $id;
    #[ORM\Column(type: 'string', length:255)]
    private $slug;
    #[ORM\Column(type: 'string', length:255)]
    private $title;
    #[ORM\Column(type: 'string', length:255)]
    private $description;
    #[ORM\Column(type: 'string', length:255)]
    private $body;
    #[ORM\Column(type: 'object')]
    private $author;
    #[ORM\Column(type: 'date')]
    private $createdAt;
    #[ORM\Column(type: 'date')]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }
    public function setAuthor(Author $author)
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    } 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
    */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));    
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

}