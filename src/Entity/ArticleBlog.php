<?php

namespace App\Entity;

use App\Repository\ArticleBlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ArticleBlogRepository::class)]
class ArticleBlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $articleTitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $articleDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $articleContent = null;

    #[ORM\OneToMany(mappedBy: 'articleBlog', targetEntity: Image::class,cascade:['persist'])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'articleBlogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $article_auth = null;

    #[Gedmo\Timestampable(on:'create')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'articleBlogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $categorie = null;

    #[ORM\PrePersist]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleTitle(): ?string
    {
        return $this->articleTitle;
    }

    public function setArticleTitle(string $articleTitle): self
    {
        $this->articleTitle = $articleTitle;

        return $this;
    }

    public function getArticleDescription(): ?string
    {
        return $this->articleDescription;
    }

    public function setArticleDescription(string $articleDescription): self
    {
        $this->articleDescription = $articleDescription;

        return $this;
    }

    public function getArticleContent(): ?string
    {
        return $this->articleContent;
    }

    public function setArticleContent(string $articleContent): self
    {
        $this->articleContent = $articleContent;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setArticleBlog($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticleBlog() === $this) {
                $image->setArticleBlog(null);
            }
        }

        return $this;
    }

    public function getArticle_Auth(): ?Users
    {
        return $this->article_auth;
    }

    public function setArticle_Auth(?Users $article_auth): self
    {
        $this->article_auth = $article_auth;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
    public function __toString()
    {
        $format = "ArticleBlog (id: %s)\n";
        return sprintf($format, $this->id);
    }

    public function getCategory(): ?Category
    {
        return $this->categorie;
    }

    public function setCategory(?Category $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


}
