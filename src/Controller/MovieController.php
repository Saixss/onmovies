<?php


namespace App\Controller;


use App\Entity\Movie;
use App\Entity\User;
use App\Service\CategoryService;
use App\Service\MovieService;
use App\Service\Paginator;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Twig\Environment;

class MovieController extends AbstractController
{
    private MovieService $movieService;

    private CategoryService $categoryService;

    private Environment $twig;

    public function __construct(MovieService $movieService,
                                CategoryService $categoryService,
                                Environment $twig)
    {
        $this->movieService = $movieService;
        $this->categoryService = $categoryService;
        $this->twig = $twig;
    }
    #[Route('/', name: 'movie_index_1')]
    #[Route('/movies', name: 'movie_index_2')]
    #[Route('/movies/page/{!page}', name: 'home', requirements: ['page' => '\d+'], priority: 5)]
    public function home(Request $request,
                         $page = 1,
                         $sort = 'id',
                         $order = 'desc'): Response
    {
        $filters = Movie::FILTERS;
        $sort = array_keys($filters,$filters[$request->query->get('sort') ?? $sort])[0];

        $orders = Movie::ORDERS;
        $order = array_keys($orders, $orders[$request->query->get('order') ?? $order])[0];

        $resultsPerPage = 40;

        $pageFirstResult = ($page - 1) * $resultsPerPage;
        $movies = $this->movieService->getMovies($pageFirstResult, $resultsPerPage, $sort, $order);

        $categories = $this->categoryService->getCategories();

        $this->movieService->setUrl($movies->getQuery()->getResult());

        $paginator = new Paginator($resultsPerPage, count($movies), $page);
        $pages = $paginator->pages($this->twig, '_home_pagination.html.twig', 20);

        return $this->render('movies.html.twig',
            [
                'movies' => $movies,
                'categories' => $categories,
                'pages' => $pages,
                'filters' => $filters,
                'orders' => $orders
            ]
        );
    }

    #[Route('/movie/{id}-{urlTitle}', name: 'movie_details')]
    public function details(#[CurrentUser] ?User $user, string $id): Response
    {
        $movie = $this->movieService->getMovieById($id);
        $categories = $this->categoryService->getCategories();

        $movieCategory = $movie->getCategories();

        $isFavorite = false;
        $isLogged = false;

        if ($user !== null) {
            $favorites = $user->getFavorites();
            $isLogged = true;
            foreach ($favorites as $favorite) {
                if($favorite->getId() == $id) {
                    $isFavorite = true;
                }
            }
        }

        return $this->render('movie_details.html.twig',
            [
                'movie' => $movie,
                'categories' => $categories,
                'movieCategory' => $movieCategory,
                'isFavorite' => $isFavorite,
                'isLogged' => $isLogged
            ]
        );
    }
}