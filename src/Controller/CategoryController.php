<?php


namespace App\Controller;


use App\Entity\Movie;
use App\Service\CategoryService;
use App\Service\MovieService;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CategoryController extends AbstractController
{
    private CategoryService $categoryService;

    private MovieService $movieService;

    private Environment $twig;


    public function __construct(CategoryService $categoryService,
                                MovieService $movieService,
                                Environment $twig)
    {
        $this->categoryService = $categoryService;
        $this->movieService = $movieService;
        $this->twig = $twig;
    }

    #[Route('/movies/{genre}', name: 'genre_index')]
    #[Route('/movies/{genre}/page/{page}',
        name: 'genre',
        requirements: ['page' => '\d+'],
        defaults: ['page' => 1])]
    public function genre(Request $request,
                          string $genre,
                          int $page = 1,
                          string $sort = 'id',
                          string $order = 'desc'): Response
    {
        $filters = Movie::FILTERS;
        $sort = array_keys($filters,$filters[$request->query->get('sort') ?? $sort])[0];

        $orders = Movie::ORDERS;
        $order = array_keys($orders, $orders[$request->query->get('order') ?? $order])[0];

        $categories = $this->categoryService->getCategories();

        $resultsPerPage = 40;
        $pageFirstResult = ($page - 1) * $resultsPerPage;
        $movies = $this->movieService->getByCategoryName($genre, $pageFirstResult, $resultsPerPage, $sort, $order);

        $this->movieService->setUrl($movies->getQuery()->getResult());

        $paginator = new Paginator($resultsPerPage, count($movies), $page);
        $pages = $paginator->pages($this->twig, '_movie_cat_pagination.html.twig', 9, ['genre' => $genre]);
        return $this->render('movies.html.twig',
            [
                'movies' => $movies,
                'categories' => $categories,
                'pages' => $pages,
                'filters' => $filters,
                'orders' => $orders
            ]);
    }
}