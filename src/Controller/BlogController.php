<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\BlogPost;
use App\Form\EntryFormType;

class BlogController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $authorRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $blogPostRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $entityManager->getRepository('App:BlogPost');
        $this->authorRepository = $entityManager->getRepository('App:Author');
    }
    
    public function createEntryAction(Request $request)
    {
        if(!($this->isGranted("ROLE_USER"))) // check if a user is logged in
        {
            return $this->redirectToRoute("app_login");
        }

        $blogPost = new BlogPost();
        
        $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserIdentifier());
        
        $blogPost->setAuthor($author);

        $form = $this->createForm(EntryFormType::class, $blogPost);
        $form->handleRequest($request);

        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost->updatedTimestamps();
            $this->entityManager->persist($blogPost);
            $this->entityManager->flush($blogPost);

            $this->addFlash('success', 'Congratulations! Your post is created');

            return $this->redirectToRoute('admin_entries');
        }

        return $this->render('admin/entry_form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    public function deleteEntryAction($id)
    {
        /*
        if(!($this->isGranted("ROLE_USER"))) // check if a user is logged in
        {
            return $this->redirectToRoute("app_login");
        }
        */
        $blogPost = $this->blogPostRepository->find($id);
        #$author = $this->authorRepository->findOneByUsername($this->getUser()->getUserIdentifier());
        $this->denyAccessUnlessGranted('POST_EDIT',$blogPost);
        $this->entityManager->remove($blogPost);
        $this->entityManager->flush();

        $this->addFlash("success", "Entry deleted successfully.");
        return $this->redirectToRoute("admin_entries"); 
    }

    public function entriesAction()
    {
        $blogPosts = [];
        $blogPosts = $this->blogPostRepository->findAll();
        
        return $this->render('blog/entries.html.twig', [
            'blogPosts' => $blogPosts
        ]);
    }

    public function detailsAction($slug): Response
    {
        if(!($this->isGranted("ROLE_USER"))) // check if a user is logged in
        {
            return $this->redirectToRoute("app_login");
        }

        #$author = $this->authorRepository->findOneByUsername($this->getUser()->getUserIdentifier());
        /*
        if(!$author)
        {
            $this->addFlash("danger", "You can't see details of a post.");
            return $this->redirectToRoute('admin_entries');
        }
        */
        $blogPosts = [];
        $blogPosts = $this->blogPostRepository->findBySlug($slug);
        #dd("View Granted");
        #$this->denyAccessUnlessGranted('POST_VIEW',$blogPosts);
        #dd("View Denied");
        return $this->render('blog/details_entries.html.twig', [
            'blogPosts' => $blogPosts
        ]);
    }

    public function editEntryAction($id, Request $request)
    {
        /*
        if(!($this->isGranted("ROLE_USER"))) // check if a user is logged in
        {
            return $this->redirectToRoute("app_login");
        }
        */
        
        $blogPost = $this->blogPostRepository->find($id);
        /*
        if(!($author == $blogPost->getAuthor()) && in_array('ROLE_ADMIN', $authorRoles) == false)
        {
            $this->addFlash("danger", "You can't edit another user's posts.");
            return $this->redirectToRoute('admin_entries');
        }
        */
        $this->denyAccessUnlessGranted('POST_EDIT',$blogPost);
        $form = $this->createForm(EntryFormType::class, $blogPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $blogPost = $form->getData();
            $blogPost->updatedTimestamps();
            
            $this->entityManager->persist($blogPost);
            $this->entityManager->flush($blogPost);
            $this->addFlash("success", "Blog post updated.");
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('admin/edit_entries.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function aboutUsAction()
    {
        return $this->render('blog/about_us.html.twig');
    }
}