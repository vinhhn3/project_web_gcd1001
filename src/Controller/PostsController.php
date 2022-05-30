<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/posts", name="show_all_posts")
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
    
    /**
     * @param $id
     * @return Response
     * @Route("/posts/{id}", name="show_post", methods={"GET"})
     */
    public function show($id) : Response
    {
        // Use ORM (Doctrine) to query a single post in Database
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Post::class);
        
        // Find a single post in Database
        $post = $repo->find($id);
        
        return $this->render('posts/show.html.twig',[
            'post' => $post
        ]);
    }
    
    /**
     * @param $id
     * @return Response
     * @Route("/posts/{id}/delete", name="delete_post", methods={"GET"})
     */
    public function delete($id): Response
    {
        // Use ORM (Doctrine) to query a single post in Database
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Post::class);
        
        // Delete a single post
        $post = $repo->find($id);
        $repo->remove($post);
        $em->flush();
        
        // Render page all_posts
        return $this->redirectToRoute("show_all_posts");
        
    }
}
