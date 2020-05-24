<?php

// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() : Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/wild/series", name="wild_series")
     */
    public function series() : Response
    {
        return $this->render('wild/series.html.twig');
    }

    /**
     * @Route("/wild/login", name="wild_login")
     */
    public function login() : Response
    {
        return $this->render('wild/login.html.twig');
    }

    /**
     * @Route("/wild/movies", name="wild_movies")
     */
    public function movies() : Response
    {
        return $this->render('wild/movies.html.twig');
    }

    /**
     * @Route("/wild/actors", name="wild_actors")
     */
    public function actors() : Response
    {
        return $this->render('wild/actors.html.twig');
    }

    /**
     * @Route("/wild/categories", name="wild_categories")
     */
    public function categories() : Response
    {
        return $this->render('wild/categories.html.twig');
    }

}