<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->findAll();
        return $this->render('blog/index.html.twig', ['articles' => $articles]);

    }

    public function add(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return new Response('L\'article a bien été enregistrer.');
        }

        return $this->render('blog/add.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    public function edit(Article $article, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdateDate(new \DateTime());

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

    public function remove($id)
    {
        return new Response('<h1>Supprimer l\'article ' . $id . '</h1>');
    }

    public function admin()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            [],
            ['updateDate' => 'DESC']
        );

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig',[
            'articles' => $articles,
            'users' => $users
        ]);
    }
}