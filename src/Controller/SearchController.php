<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Service\CategoryService;
use App\Service\MovieService;
use App\Service\Paginator;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class SearchController extends AbstractController
{
    private MovieService $movieService;

    private CategoryService $categoryService;

    public function __construct(MovieService $movieService, CategoryService $categoryService)
    {
        $this->movieService = $movieService;
        $this->categoryService = $categoryService;
    }

    #[Route('/search', name: 'app_search', methods: ['POST'], )]
    #[Route('/search/page/{!page}', name: 'app_search_page', requirements: ['page' => '\d+'], methods: ['POST'])]
    public function search(Request $request, Environment $twig, $page = 1): Response
    {
        $searchData = $request->get('searchData');

        $movies = $this->movieService->getMoviesBySearchData($searchData);
        $categories = $this->categoryService->getCategories();

        $numOfMovies = count($movies);

        $resultsPerPage = 40;
        $pageFirstResult = ($page - 1) * $resultsPerPage;

        $paginator = new Paginator($resultsPerPage, $numOfMovies, $page);

        $pages = $paginator->pages($twig, '_home_pagination.html.twig', 9);

        return $this->render('movies.html.twig',
            [
                'movies' => $movies,
                'categories' => $categories,
                'pages' => $pages
            ]);
    }

    #[Route('/api/search', methods: ['POST'])]
    public function apiSearch(Request $request): JsonResponse
    {
        $searchData = $request->get('searchData');

        try {
            $movies = $this->movieService->getMoviesBySearchData($searchData);

            return $this->json($movies, headers: ['Content-Type' => 'application/json;charset=UTF-8']);
        } catch (Exception | \ErrorException | \Error | FatalError $error) {
            return $this->json($error, headers: ['Content-Type' => 'application/json;charset=UTF-8']);
        }
    }
}