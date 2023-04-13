<?php


namespace App\Service;


use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MovieService
{
    private MovieRepository $movieRepository;

    private EntityManagerInterface $entityManager;

    private UrlEncoder $urlEncoder;

    public function __construct(MovieRepository $movieRepository,
                                EntityManagerInterface $entityManager,
                                UrlEncoder $urlEncoder)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
        $this->urlEncoder = $urlEncoder;
    }

    public function save(Movie $movie)
    {
        $this->movieRepository->save($movie);
    }

    public function getMovies(int $pageFirstResult, int $resultsPerPage, string $sort, string $order): Paginator
    {
       return $this->movieRepository->findByPage($pageFirstResult, $resultsPerPage, $sort, $order);
    }

    public function getMoviesByTitle(string $name)
    {
        return $this->movieRepository->findBy(['title' => $name]);
    }

    public function getMovieById(string $id): ?Movie
    {
        return $this->movieRepository->findOneBy(['id' => $id]);
    }

    public function getByCategoryName(string $categoryName, int $pageFirstResult, int $resultsPerPage, string $sort, string $order): Paginator
    {
        return $this->movieRepository->getByCategoryName($categoryName, $pageFirstResult, $resultsPerPage, $sort, $order);
    }

    public function setUrl(array $movies)
    {
        /**
         *  @var Movie[] $movies
         */

        foreach ($movies as $movie) {
            if ($movie->getUrlTitle() === NULL) {
                $movie->setUrlTitle($this->urlEncoder->encode($movie->getTitle()));
                $this->entityManager->flush();
            }
        }
    }
}