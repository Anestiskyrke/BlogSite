<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\RegistrationFormType;
use App\Form\AuthorFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthorController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $authorRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->authorRepository = $entityManager->getRepository('App:Author');
    }
    
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Author();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            if($user->getUserIdentifier() === 'codeseed')
            {
                $user->setRoles(['ROLE_ADMIN']);
            }    
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function editProfile(Request $request): Response
    {
        if(!($this->isGranted("ROLE_USER"))) // check if a user is logged in
        {
            return $this->redirectToRoute("app_login");
        }

        $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserIdentifier());
        $form = $this->createForm(AuthorFormType::class, $author);
        $form->handleRequest($request);

        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($author);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your profile changes have been saves');

            return $this->redirectToRoute('admin_entries');
        }

        return $this->render('admin/profile_form.html.twig', [
            'authorForm' => $form->createView(),
            'profileImage' => $author->getProfileImage()
        ]);
    }

}
