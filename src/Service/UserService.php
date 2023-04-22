<?php


namespace App\Service;


use App\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getFavById(string $movieId, string $userId)
    {
        return $this->userRepository->findFavMovieById($movieId, $userId);
    }

    public function findById(string $userId): ?\App\Entity\User
    {
        return $this->userRepository->find($userId);
    }

    public function getFavorites(int $userId, int $resultStart)
    {
        return $this->userRepository->findFavoritesByUserId($userId, $resultStart);
    }
}