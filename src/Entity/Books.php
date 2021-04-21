<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BooksRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BooksRepository::class)
 *
 * @ApiResource(
 *      collectionOperations = {
 *          "get"={
 *              "method"="GET",
 *              "path"="/album.{_format}",
 *          },
 *          "post"={
 *              "method"="POST",
 *              "path"="/album.{_format}",
 *          },
 *      },
 *     itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/album/{id}.{_format}",
 *          },
 *     }
 * )
*/
class Books
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     */
    private $author;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $url;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" = 0})
     *
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title.' by '.$this->author;
    }
}
