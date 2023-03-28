<?php

namespace App\Entity;

use App\Repository\MoviePictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoviePictureRepository::class)]
class MoviePicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'moviePictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?movie $movie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMovie(): ?movie
    {
        return $this->movie;
    }

    public function setMovie(?movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
