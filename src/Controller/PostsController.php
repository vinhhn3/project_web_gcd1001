<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/posts", name="app_posts")
     */
    public function index(): Response
    {
        // Use ORM (Doctrine) to query all posts in Database
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Post::class);
        
        // Query all Posts in Database
        $posts = $repo->findAll();
        
        return $this->render('posts/index.html.twig', [
            'controller_name' => 'PostsController',
            'posts' => $posts
            
        ]);
    }
}
