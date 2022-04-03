<?php

namespace App\Security\Voter;

use App\Entity\BlogPost;
use App\Entity\Author;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class BlogPostVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        #dd($attribute);
        #dd($subject);
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\BlogPost;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof Author) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $blogPost = $subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return true;
            case self::EDIT:
                // logic to determine if the user can EDIT
                return $this->canEdit($blogPost,$user);
                // return true or false
            
        }

        throw new \LogicException('This code should not be reached!');

    }

    private function canEdit(BlogPost $blogPost, Author $user):bool
    {   
        return $user->getID() == $blogPost->getAuthor()->getID();
    }
}
