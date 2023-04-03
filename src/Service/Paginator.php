<?php


namespace App\Service;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class Paginator
{
    private int $resultCount;

    private int $maxResults;

    private int $page;

    private int $totalPages;


    public function __construct(int $resultsPerPage, int $resultCount, int $page)
    {
        $this->maxResults = $resultsPerPage;
        $this->resultCount = $resultCount;
        $this->setTotalPages();
        $this->setPage($page);
    }

    public function pages(Environment $twig,
                          string $paginationTemplate,
                          int $numOfVisiblePages,
                          array $params = []): string
    {
        if($numOfVisiblePages === 0) {
            $numOfVisiblePages = 1;
        }

        if($this->totalPages <= $numOfVisiblePages) {
            $numOfVisiblePages = $this->totalPages;
        }

        $middleIndex = floor($numOfVisiblePages / 2);

        $evenModifier = 0;

        if($numOfVisiblePages % 2 == 0) {
            $evenModifier = 1;
        }

        $startIndexValue = $this->page - $middleIndex + $evenModifier;
        $endIndexValue = $this->page + $middleIndex;

        if($startIndexValue <= 0) {
            $endIndexValue += abs($startIndexValue - 1);
            $startIndexValue = 1;
        }

        if($endIndexValue > $this->totalPages) {
            $startIndexValue -= $endIndexValue - $this->totalPages;
            $endIndexValue = $this->totalPages;
        }

        $visiblePages = range($startIndexValue, $endIndexValue);

        $params['visiblePages'] = $visiblePages;
        $params['paginator'] = $this;
        $paginationContents = $twig->render($paginationTemplate, $params);
        return $paginationContents;
    }

    public function prev(): ?int
    {
        if ($this->page > 1) {
            return $this->page - 1;
        } else {
            return null;
        }
    }

    public function next(): ?int
    {
        if ($this->page < $this->totalPages) {
            return $this->page + 1;
        } else {
            return null;
        }
    }

    private function setPage(int $page)
    {
        if ($page > $this->totalPages) {
            throw new NotFoundHttpException('Page not found.');
        }

        $this->page = $page;
    }

    private function setTotalPages()
    {
        if ($this->resultCount !== 0) {
            $this->totalPages = ceil($this->resultCount / $this->maxResults);
        } else {
            $this->totalPages = 1;
        }
    }
}