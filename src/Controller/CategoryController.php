<?php

// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/add", name="add")
     */
    public function add(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(
                    CategoryType::class,
                    $category,
                    ['method' => Request::METHOD_POST]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $categoryManager = $this->getDoctrine()->getManager();
            $categoryManager->persist($data);
            $categoryManager->flush();
        }
        return $this->render(
            'category/add.html.twig', [
                'form' => $form->createView()
            ]
        );
    }
}