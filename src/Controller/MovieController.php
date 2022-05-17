<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Form\MovieSearchType;
use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MovieController extends AbstractController
{
    /**
     * @var array
     */
    private $movie;

    public function __construct(MovieRepository $movieRepository, Security $security)
    {
        $this->movieRepository = $movieRepository;
        $this->security = $security;
        // without db way :
        // $package = new Package(new EmptyVersionStrategy());
        // $path = $package->getUrl('assets/json/movies.json');
        // $str_json = file_get_contents($path);
        // $arr = json_decode($str_json, true);
        // // dd($arr);
        // $moviesArray = $arr['results'];
       
        // foreach ($moviesArray as $item) {
        //     $movie = new Movie();
        //     $movie->setId($item['id']);
        //     $lang = $item['original_language'] == "fr" ? "VF" : "VOST";
        //     $movie->setOriginalLanguage($lang);
        //     $movie->setOriginalTitle($item['original_title']);
        //     $movie->setOverview($item['overview']);
        //     $movie->setPopularity($item['popularity']);
        //     $movie->setPosterPath($item['poster_path']);
        //     $movie->setReleaseDate($item['release_date']);
        //     $movie->setTitle($item['title']);
        //     $movie->setVideo($item['video']);
        //     $movie->setVoteAverage($item['vote_average']);
        //     $movie->setVoteCount($item['vote_count']);
        //     $movie->setAdult($item['adult']);
        //     $movie->setBackdropPath($item['backdrop_path']);
        //     // $movie->setGenreIds($item['genre_ids']);
        //     $this->movies[$item['id']] = $movie;
        // }
    }

    /**
     * @Route("/movie_list", name="movie_list")
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
        if(!empty($user))
        {
            $user = $this->security->getUser();
        
            $favorites = $user->getFavoriteGenres();
    
            // $suggest = $this->movieRepository->findByGenres($favorites);
        }
  


        return $this->render('Movie/index.html.twig', [
            'searchTitle' => $searchTitle, // appel var
            'movies' => $movies, // appel var
            'form' => $form->createView(),
            // 'suggest' => $suggest
        ]);

        // dd($this->movies);
        // return $this->render('Movie/index.html.twig', [
        //     'movies' => $this->movies, // appel var
        // ]);
    }


}
