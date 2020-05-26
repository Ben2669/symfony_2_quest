<?php

// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */
Class WildController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * @Route("/series", name="series")
     */
    public function series() : Response
    {
        return $this->render('wild/series.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function login() : Response
    {
        return $this->render('wild/login.html.twig');
    }

    /**
     * @Route("/movies", name="movies")
     */
    public function movies() : Response
    {
        return $this->render('wild/movies.html.twig');
    }

    /**
     * @Route("/actors", name="actors")
     */
    public function actors() : Response
    {
        return $this->render('wild/actors.html.twig');
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function categories() : Response
    {
        return $this->render('wild/categories.html.twig');
    }

    /**
     * @Route("/show/{slug}", requirements={"slug"="^[0-9-a-z]+$"}, defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"}, name="show")
     */
    public function show(?string $slug) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table .');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/category/{categoryName}", requirements={"categoryName"="^[0-9-a-z]+$"}, name="show_category")
     */
    public function showByCategory(string $categoryName) : Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No categoryName has been sent to find a category in category\'s table .');
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => ucfirst($categoryName)]);
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with '.$categoryName.' name, found in category\'s table.'
            );
        }
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category->getId()], ['id' => 'DESC'], 3);

        return $this->render('wild/category.html.twig', [
            'category' => $category->getName(),
            'programs' => $programs,
        ]);
    }

}