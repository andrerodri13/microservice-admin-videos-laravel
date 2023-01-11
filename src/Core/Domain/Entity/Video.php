<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\Factory\VideoValidatorFactory;
use Core\Domain\Notification\NotificationException;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Video extends Entity
{
    protected array $categoriesId = [];
    protected array $genresId = [];
    protected array $castMembersId = [];

    public function __construct(
        protected string    $title,
        protected string    $description,
        protected int       $yearLaunched,
        protected int       $duration,
        protected bool      $opened,
        protected Rating    $rating,
        protected ?Uuid     $id = null,
        protected bool      $published = false,
        protected ?DateTime $createdAt = null,
        protected ?Image    $thumbFile = null,
        protected ?Image    $thumbHalf = null,
        protected ?Media    $trailerFile = null,
        protected ?Media    $videoFile = null,
        protected ?Image    $bannerFile = null,
    )
    {
        parent::__construct();

        $this->id = $this->id ?? Uuid::ramdom();
        $this->createdAt = $this->createdAt ?? new DateTime();
        $this->validation();
    }


    public function addCategoryId(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategoryId(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    public function addGenreId(string $genreId)
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenreId(string $genreId)
    {
        unset($this->genresId[array_search($genreId, $this->genresId)]);
    }

    public function addCastMemberId(string $castMemberId)
    {
        array_push($this->castMembersId, $castMemberId);
    }

    public function removeCastMemberId(string $castMemberId)
    {
        unset($this->castMembersId[array_search($castMemberId, $this->castMembersId)]);
    }

    public function thumbFile(): ?Image //? - Obj Image ou null
    {
        return $this->thumbFile;
    }

    public function thumbHalf(): ?Image //? - Obj Image ou null
    {
        return $this->thumbHalf;
    }

    public function bannerFile(): ?Image //? - Obj Image ou null
    {
        return $this->bannerFile;
    }

    public function trailerFile(): ?Media //? - Obj Media ou null
    {
        return $this->trailerFile;
    }

    public function videoFile(): ?Media //? - Obj Media ou null
    {
        return $this->videoFile;
    }

    protected function validation()
    {
        VideoValidatorFactory::create()->validate($this);


        if ($this->notification->hasErrors()) {
            throw new NotificationException(
                $this->notification->messages('video')
            );
        }
    }

}
