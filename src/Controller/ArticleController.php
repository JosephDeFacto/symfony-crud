<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article_list")
     */
    public function index()
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $article = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', ['article' => $article]);

        //return $this->render('article/article.html.twig', ['article' => $article]);
    }

    /**
     * @param Request $request
     * @Route("/article/create", name="article_create")
     * @return Response
     */
    public function create(Request $request)
    {



        $article = new Article();

        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $created_at = new \DateTime("NOW");
            $article->setCreatedAt($created_at);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     */

    public function edit($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('article_show');
        }

        return $this->render('article/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param $id
     * @param Request $request
     * @Route("/article/delete/{id}", name="article_delete")
     * @return Response
     */
    public function delete($id, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $this->getDoctrine()->getManager()->remove($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('article_list');
    }
}
