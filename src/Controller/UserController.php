<?php

namespace App\Controller;


use App\Entity\Movie;
use App\Entity\User;
use App\Form\RegisterType;
use App\Form\UserType;
use App\Service\CategoryService;
use App\Service\FileUploader;
use App\Service\MovieService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request,
                             ManagerRegistry $doctrine,
                             UserPasswordHasherInterface $passwordHasher,
                             ValidatorInterface $validator): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('movie_index_1');
        }

        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        $user = $form->getData();
        $errors = $validator->validate($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', ['form' => $form, 'errors' => $errors]);
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request,
                          AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('movie_index_1');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }

    #[Route('/user/profile', name: 'user_profile')]
    public function profile(#[CurrentUser] ?User $user,
                            CategoryService $categoryService,
                            UserService $userService): Response|RedirectResponse
    {
        if ($user === null) {
           return $this->redirectToRoute('login');
        }

        $categories = $categoryService->getCategories();

        $favorites = $userService->getFavorites($user->getId(), 0);

        return $this->render('user/profile.html.twig',
            [
                'categories' => $categories,
                'user' => $user,
                'favorites' => $favorites
            ]);
    }

    #[Route('/api/user/favorites/{!from}', methods: ['GET'])]
    public function getFavorites(#[CurrentUser] ?User $user, UserService $userService, SerializerInterface $serializer, int $from = 1): JsonResponse
    {
        if ($user === null) {
            return $this->json('', 401);
        }

        if ($from < 1) {
            $from = 1;
        }

        $favorites = $userService->getFavorites($user->getId(), $from);
        $favoritesSerialized = $serializer->serialize($favorites, 'json');

        return $this->json($favoritesSerialized, 200, ['Content-type' => 'application/json']);
    }

    #[Route('/user/edit-profile', name: 'user_edit_profile')]
    public function editProfile(#[CurrentUser] ?User $user,
                                Request $request,
                                CategoryService $categoryService,
                                EntityManagerInterface $entityManager,
                                FileUploader $fileUploader): RedirectResponse|Response
    {
        if ($user === null) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('profilePicture')->getData();

            if ($pictureFile) {
                $newFilename = $fileUploader->upload($pictureFile);

                $fileUploader->remove($user->getProfilePictureFilename());
                $user->setProfilePictureFilename($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_profile');
        }

        $categories = $categoryService->getCategories();

        return $this->render('user/edit_profile.html.twig',
            [
                'user' => $user,
                'form' => $form,
                'categories' => $categories
            ]);
    }

    #[Route('/user/add-favorite/', name: 'add_favorite', methods: 'POST')]
    public function addFavoriteMovie(#[CurrentUser] ?User $user,
                                     Request $request,
                                     MovieService $movieService,
                                     EntityManagerInterface $entityManager): JsonResponse
    {
        $movieId = $request->request->get('movieId');

        $flashMessage = 'Added to favorites';
        $htmlDisplayMessage = 'Remove favorite';
        $isBtnActive = true;

        if (null === $user) {
            return $this->json([
                 'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $favorites = $user->getFavorites();
        $hasFavorite = false;

        foreach ($favorites as $favorite) {
            if ($favorite->getId() === (int)$movieId) {
                $user->removeFavorite($favorite);
                $flashMessage = 'Removed from favorites';
                $htmlDisplayMessage = 'Add favorite';
                $isBtnActive = false;
                $hasFavorite = true;
            }
        }

        if ($hasFavorite === false) {
            $movie = $movieService->getMovieById($movieId);
            $user->addFavorite($movie);
        }

        $entityManager->flush();

        return $this->json(
            [
                'flashMessage' => $flashMessage,
                'htmlDisplayMessage' => $htmlDisplayMessage,
                'isBtnActive' => $isBtnActive
            ],
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}