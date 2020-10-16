<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\ArticleType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;



class BlogController extends AbstractController
{
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findAll();
        return $this->render('blog/index.html.twig', ['articles' => $articles]);

    }

    public function add(Request $request, FileUploader $fileUploader)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime());
            $images = $form->get('images')->getData();
            $videos = $form->get('videos')->getData();

            foreach($images as $image)
            {
                $image = $fileUploader->upload($image['file']);
                $img = new Image();
                $img->setName($image);
                $article->addImage($img);
            }

            foreach($videos as $video)
            {
                $article->addVideo($video);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return new Response('L\'article a bien été enregistrer.');
        }

        return $this->render('blog/add.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    public function edit(Article $article, Request $request, FileUploader $fileUploader)
    {

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $article->setUpdateDate(new \DateTime());
            $images = $form->get('images')->getData();
            $videos = $form->get('videos')->getData();

            /*foreach($images as $image)
            {
                if(false === $article->getImages()->contains($images))
                {
                    $path = $this->getParameter('images_directory').'/'.$image->getName();
                    unlink($path);
                    $article->getImages()->removeElement($images);

                }
            }*/

            foreach($videos as $video)
            {
                $article->addVideo($video);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return new Response('L\'article a bien été modifié.');
        }
        return $this->render('blog/edit.html.twig', [
            'article' => $article,
            'form'=> $form->createView()
        ]);
    }

    public function remove(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return new Response('L\'article a bien été supprimer.');
    }
}

