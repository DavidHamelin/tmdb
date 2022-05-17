<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $jsonGenres = json_decode(file_get_contents(__DIR__ . '/../../public/assets/json/genres.json'));
        // possibilité de le faire également sans tableau cad en objet (->) en retirant le parametre 'true' dans la ligne ci-dessus
        $genresArray = $jsonGenres->genres;

        foreach ($genresArray as $itemGenre) {
            $genre = new Genre();
            // $genre->setId($itemGenre->id);
            $genre->setJsonId($itemGenre->id);
            $genre->setName($itemGenre->name);
            $manager->persist($genre);
        }
        $manager->flush();

        $repositoryGenre = $manager->getRepository(Genre::class);

        $json = json_decode(file_get_contents(__DIR__ . '/../../public/assets/json/movies.json'));
        // possibilité de le faire également sans tableau cad en objet (->) en retirant le parametre 'true' dans la ligne ci-dessus
        $moviesArray = $json->results;

        foreach ($moviesArray as $item) {
            $movie = new Movie();
            // $movie->setId($item->id);
            $movie->setOriginalLanguage($item->original_language);
            $movie->setOriginalTitle($item->original_title);
            $movie->setOverview($item->overview);
            $movie->setPopularity($item->popularity);
            $movie->setPosterPath($item->poster_path);
            // $movie->setReleaseDate($item->release_date);
            $movie->setReleaseDate(\DateTime::createFromFormat('Y-m-d', $item->release_date));
            $movie->setTitle($item->title);
            $movie->setVideo($item->video);
            $movie->setVoteAverage($item->vote_average);
            $movie->setVoteCount($item->vote_count);
            $movie->setAdult($item->adult);
            $movie->setBackdropPath($item->backdrop_path);
            foreach ($item->genre_ids as $genre_id_tmdb) {
                $genre = $repositoryGenre->findOneBy(array('jsonId' => $genre_id_tmdb));
                // dd($genre);
                $movie->addGenreId($genre);
            }
            $manager->persist($movie);
        }

        $manager->flush();
    }

    // public function getDependencies()
    // {
    //     return [
    //         AppFixtures::class,
    //     ];
    // }
}
