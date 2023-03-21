<?php

namespace App\Entity\Blog;

use App\Repository\Blog\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true, nullable: false)]
    private string $seoUrl;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ogTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ogDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ogImage = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getPosts(): ArrayCollection|Collection
    {
        return $this->posts;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Category
    {
        $this->title = $title;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setSeoUrl(string $seoUrl): self
    {
        $this->seoUrl = $seoUrl;
        return $this;
    }

    public function getSeoUrl(): string
    {
        return $this->seoUrl;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;
        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    public function setOgTitle(?string $ogTitle): Category
    {
        $this->ogTitle = $ogTitle;
        return $this;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function setOgImage(?string $ogImage): Category
    {
        $this->ogImage = $ogImage;
        return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function setOgDescription(?string $ogDescription): self
    {
        $this->ogDescription = $ogDescription;
        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
