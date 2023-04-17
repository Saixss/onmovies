<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\Index(columns: ['title'], name: 'title_idx')]

class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'movies')]
    #[ORM\JoinTable(name: 'movie_category')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorites')]
    private Collection $users;

    #[ORM\Column(length: 255)]
    private ?string $posterUrl = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MoviePicture::class)]
    private Collection $moviePictures;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $released = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $runtime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $director = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $writer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $actors = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $plot = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $urlTitle = null;

    public const FILTERS = ['title' => 'Title', 'released' => 'Release Date', 'rating' => 'IMDb Rating', 'id' => 'Id'];

    public const ORDERS = ['asc' => 'Ascending', 'desc' => 'Descending'];

    private string $filter;

    private string $order;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->moviePictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

    public function getPosterUrl(): ?string
    {
        return $this->posterUrl;
    }

    public function setPosterUrl(string $posterUrl): self
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }

    /**
     * @return Collection<int, MoviePicture>
     */
    public function getMoviePictures(): Collection
    {
        return $this->moviePictures;
    }

    public function addMoviePicture(MoviePicture $moviePicture): self
    {
        if (!$this->moviePictures->contains($moviePicture)) {
            $this->moviePictures->add($moviePicture);
            $moviePicture->setMovie($this);
        }

        return $this;
    }

    public function removeMoviePicture(MoviePicture $moviePicture): self
    {
        if ($this->moviePictures->removeElement($moviePicture)) {
            // set the owning side to null (unless already changed)
            if ($moviePicture->getMovie() === $this) {
                $moviePicture->setMovie(null);
            }
        }

        return $this;
    }

    public function getReleased(): ?\DateTimeInterface
    {
        return $this->released;
    }

    public function setReleased(?\DateTimeInterface $released): self
    {
        $this->released = $released;

        return $this;
    }

    public function getRuntime(): ?\DateTimeInterface
    {
        return $this->runtime;
    }

    public function setRuntime(?\DateTimeInterface $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(?string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getWriter(): ?string
    {
        return $this->writer;
    }

    public function setWriter(?string $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function getActors(): ?string
    {
        return $this->actors;
    }

    public function setActors(?string $actors): self
    {
        $this->actors = $actors;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(?string $plot): self
    {
        $this->plot = $plot;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getUrlTitle(): ?string
    {
        return $this->urlTitle;
    }

    public function setUrlTitle(?string $urlTitle): self
    {
        $this->urlTitle = $urlTitle;

        return $this;
    }

    public function getFilter(): string
    {
        return $this->filter;
    }

    public function setFilter(string $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setOrder(string $order): self
    {
        $this->order = $order;

        return $this;
    }
}
