<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/posts/create", name="create_post", methods={"GET", "POST"})
     */
    public function create(Request $request) : Response{
        $post = new Post();
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);
        
        if ($postForm->isSubmitted() && $postForm->isValid()){
            $data = $postForm->getData();
            $post->setContent($data->content);
            $post->setCreatedAt($data->createdAt);
            
            // Use ORM (Doctrine) to query a single post in Database
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository(Post::class);
            
            $repo->add($post);
            $em->flush();
            return $this->redirectToRoute('show_all_posts');
        }
        
        return $this->render('posts/create.html.twig',[
            'post_form' => $postForm->createView()
        ]);
    }
    
    /**
     * @Route("/posts/update/{id}", name="update_post", methods={"GET", "POST"})
     */
    public function update($id, Request $request){
        // Use ORM (Doctrine) to query a single post in Database
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Post::class);
        $post = $repo->find($id);
        
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);
        
        if ($postForm->isSubmitted() && $postForm->isValid()){
            $data = $postForm->getData();
            $post->setContent($data->content);
            $post->setCreatedAt($data->createdAt);
            $repo->add($post);
            $em->flush();
            return $this->redirectToRoute('show_all_posts');
        }
        
        return $this->render('posts/update.html.twig',[
            'post_form' => $postForm->createView()
        ]);
    }
    

    /**
     * @Route("/posts", name="show_all_posts", methods={"GET"})
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
     * @Route("/posts/get/{id}", name="show_post", methods={"GET"})
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
     * @Route("/posts/delete/{id}", name="delete_post", methods={"GET"})
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
