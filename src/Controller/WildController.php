<?php

// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request) : Response
    {
        $form = $this->createForm(
                    ProgramSearchType::class,
                    null,
                    ['method' => \Symfony\Component\HttpFoundation\Request::METHOD_GET]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
        }
        $category = new Category();
        $formBis = $this->createForm(
                        CategoryType::class,
                        $category,
                        ['method' => Request::METHOD_GET]
        );
        $formBis->handleRequest($request);
        if ($formBis->isSubmitted() && $formBis->isValid()) {
            $dataBis = $formBis->getData();
            $categoryManager = $this->getDoctrine()->getManager();
            $categoryManager->persist($dataBis);
            $categoryManager->flush();
        }
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig', [
                'programs' => $programs,
                'form' => $form->createView(),
                'formBis' => $formBis->createView()
                ]
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

    /**
     * @Route("/program/{programName}", requirements={"categoryName"="^[0-9-a-z]+$"}, name="show_program")
     */
    public function showByProgram(string $programName) : Response
    {
        if (!$programName) {
            throw $this
                ->createNotFoundException('No programName has been sent to find a category in program\'s table .');
        }
        $programName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($programName)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => $programName]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No category with '.$programName.' name, found in category\'s table.'
            );
        }
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program->getId()]);

        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/season/{id}", requirements={"id"="^[0-9]+$"}, name="show_season")
     */
    public function showBySeason(int $id) : Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find a season in season\'s table .');
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);
        if (!$id) {
            throw $this->createNotFoundException(
                'No season with '.$id.' found in season\'s table.'
            );
        }
        $program = $season->getProgram();
        $episodes = $season->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route("/episode/{id}", requirements={"id"="^[0-9]+$"}, name="show_episode")
     */
    public function showByEpisode(Episode $episode) : Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }



}