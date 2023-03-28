<?php


namespace App\Service;


use App\Repository\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function getByName(string $categoryName)
    {
        return $this->categoryRepository->getByName($categoryName);
    }
}