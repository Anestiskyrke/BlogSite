<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use App\Entity\BlogPost;
use App\Form\EntryFormType;
use App\Form\SearchBlogPostType;

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
            $this->blogPostRepository->add($blogPost,true);

            $this->addFlash('success', 'Congratulations! Your post is created');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('admin/entry_form.html.twig', [
            'blogPost' => $blogPost,
            'form' => $form->createView()
        ]);
    }
    
    public function deleteEntryAction($id)
    {
        $blogPost = $this->blogPostRepository->find($id);
        $this->denyAccessUnlessGranted('POST_EDIT',$blogPost);
        $this->entityManager->remove($blogPost);
        $this->entityManager->flush();

        $this->addFlash("success", "Entry deleted successfully.");
        return $this->redirectToRoute("admin_entries"); 
    }

    public function entriesAction(Request $request): Response
    {
        $blogPosts = [];
        $blogPost = new BlogPost();
        $form = $this->createForm(SearchBlogPostType::class, $blogPost);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid())
        {
            $value = $form->getData()->getCategory();
            if($value == null)
                $blogPosts = $this->blogPostRepository->findAll();
            else
                $blogPosts = $this->blogPostRepository->findByCategory($value);
            
            return $this->render('blog/entries.html.twig', [
                'blogPosts' => $blogPosts,
                'form' => $form->createView()
            ]);
        }
        return $this->render('blog/entries.html.twig', [
            'blogPosts' => $this->blogPostRepository->findAll(),
            'form' => $form->createView()
        ]);
        
    }

    public function searchAction(Request $request): Response
    {
        $blogPosts = [];
        $blogPost = new BlogPost();
        $form = $this->createForm(SearchBlogPostType::class, $blogPost);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid())
        {
            $value = $form->getData()->getCategory();
            $blogPosts = $this->blogPostRepository->findByCategory($value);
            
            return $this->render('blog/entries.html.twig', [
                'blogPosts' => $blogPosts
            ]);
        }
        return $this->render('blog/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function detailsAction($slug): Response
    {
        $blogPost = $this->blogPostRepository->findOneBySlug($slug);
        $this->denyAccessUnlessGranted('POST_VIEW',$blogPost);
        $author = $this->authorRepository->findOneById($blogPost->getAuthor()->getId());
        $profileImage = $author->getProfileImage();
        $relatedPosts = $blogPost->getRelatedPosts();
        
        return $this->render('blog/details_entries.html.twig', [
            'blogPost' => $blogPost,
            'relatedPosts' => $relatedPosts,
            'profileImage' => $profileImage
        ]);
    }

    public function editEntryAction($id, Request $request)
    {
        $blogPost = $this->blogPostRepository->find($id);
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
            'form' => $form->createView(),
            'imageURL' => $blogPost->getImageURL()
        ]);
    }

    public function aboutUsAction()
    {
        return $this->render('blog/about_us.html.twig');
    }
}