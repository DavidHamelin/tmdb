<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieSearchType;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/movie")
 * 
 */
class AdminMovieController extends AbstractController
{
    /**
     * @var array
     */
    private $movie;

      /**
     * @var string
     */
    private $img_path = '/uploads/images/movies/';

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }
    /**
     * @Route("/", name="app_admin_movie")
     */
    public function index(Request $request): Response
    {
        $movieSearch = new Movie();
        $form = $this->createForm(MovieSearchType::class, $movieSearch);
        $form->handleRequest($request); // get request

        $movies = $this->movieRepository->findByOptions($movieSearch);

        $searchTitle = "Dernières sorties ciné";
        if ($form->isSubmitted() && $form->isValid()) {
            $searchTitle = "Films triés par : ";

            // !empty($request->request->get("energyOption")) : avec methode getBlockPrefix dans CarSearchType
            if (isset($_POST["genre_ids"]) && count($_POST["genre_ids"]) > 0) {
                $searchTitle .= "GENRES ";
            }
            if (isset($_POST["title"]) && $_POST["title"] != null) {

                $searchTitle .= strpos($searchTitle, "GENRES ") !== false ? "& TITRES " : "TITRES ";
            }
        }

        return $this->render('admin_movie/index.html.twig', [
            'searchTitle' => $searchTitle, // appel var
            'movies' => $movies, // appel var
            'form' => $form->createView(),
            'img_path' => $this->img_path
        ]);
    }

        /**
     * @Route("/edit/{id}", name="app_admin_movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie);
            $this->addFlash(
                'Success',
                'Changes saved !'
            );
            return $this->redirectToRoute('app_admin_movie');
        }

        return $this->renderForm('admin_movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie);
            $this->addFlash(
                'Success',
                'Movie added to database !'
            );
            return $this->redirectToRoute('app_admin_movie');
        }

        return $this->renderForm('admin_movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form

        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie);
            $this->addFlash(
                'Success',
                'Movie removed !'
            );
        }

        return $this->redirectToRoute('app_admin_movie');
    }
}
