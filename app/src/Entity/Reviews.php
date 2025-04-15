<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $episodeName;

    #[ORM\Column(name: 'episode_id',type: 'integer', nullable: false)]
    private int $episodeId;

    #[ORM\Column(name: 'release_date', type:'datetime', nullable: false)]
    private datetime $releaseDate;

    #[ORM\Column(name: 'score',type: 'float', nullable: true)]
    private float $score = 0;

    #[ORM\Column(name: 'episode_comment',length: 500, nullable: false)]
    private ?string $episodeComment;

    #[ORM\Column(name: 'create_date', type:'datetime', nullable: false)]
    private datetime $createDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpisodeName(): ?string
    {
        return $this->episodeName;
    }

    public function setEpisodeName(?string $episodeName): void
    {
        $this->episodeName = $episodeName;
    }

    public function getEpisodeId(): int
    {
        return $this->episodeId;
    }

    public function setEpisodeId(int $episodeId): void
    {
        $this->episodeId = $episodeId;
    }

    public function getReleaseDate(): DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTime $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function setScore(float $score): void
    {
        $this->score = $score;
    }

    public function getEpisodeComment(): ?string
    {
        return $this->episodeComment;
    }

    public function setEpisodeComment(?string $episodeComment): void
    {
        $this->episodeComment = $episodeComment;
    }

    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(DateTime $createDate): void
    {
        $this->createDate = $createDate;
    }

}
