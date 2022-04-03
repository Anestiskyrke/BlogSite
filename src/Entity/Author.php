<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use App\Entity\BlogPost;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]


class Author implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\Column(type: 'string', length:255)]
    private $username;
    #[ORM\Column(type: 'string', length:255)]
    private $password;
    #[ORM\Column(type: 'string', length:255)]
    private $email;
    #[ORM\Column(type: 'string', length:255)]
    private $name;
    #[ORM\Column(type: 'string', length:255)]
    private $profileImage;
    #[ORM\Column(type: 'integer')]
    private $phone;
    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: BlogPost::class, fetch:'EAGER')]
    private $blogPosts;

    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
    }
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getProfileImage()
    {
        return $this->profileImage;
    }
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;

        return $this;
    }
    
    public function getPhone(): ?int
    {
        return $this->phone;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
   
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogPost $blogPost): self
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts[] = $blogPost;
            $blogPost->setAuthor($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): self
    {
        if ($this->blogPosts->removeElement($blogPost)) {
            // set the owning side to null (unless already changed)
            if ($blogPost->getAuthor() === $this) {
                $blogPost->setAuthor(null);
            }
        }

        return $this;
    }
}