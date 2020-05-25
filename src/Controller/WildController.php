<?php

// src/Controller/WildController.php
namespace App\Controller;

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
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
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
    public function show(string $slug) : Response
    {
        $slugArray = explode("-", $slug);
        $newSlug = ucwords(implode(" ", $slugArray));
        return $this->render('wild/show.html.twig', ['slug' => $newSlug]);
    }

}