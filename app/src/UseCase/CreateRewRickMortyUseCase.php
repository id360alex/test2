<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Entity\Reviews;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use NickBeen\RickAndMortyPhpApi\Episode;
use NickBeen\RickAndMortyPhpApi\Api;
use App\Service\SentimentAnalysis\SentimentAnalysis;
use NickBeen\RickAndMortyPhpApi\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateRewRickMortyUseCase implements CreateRewRickMortyUseCaseInterface
{

    public function __construct(
        private readonly SentimentAnalysis      $sentimentAnalysis,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    /**
     * @throws NotFoundException
     * @throws \Exception
     */
    public function handle($request, array $queryParams): ?int
    {

        $episodeId = $queryParams['episodeId'] ?? null;
        $episodeName = $queryParams['episodeName'] ?? null;
        $comment = $queryParams['comment'] ?? '';
        $episodes = new Episode();
        foreach ($episodes->get()->results as $episod) {
            if ($episod->id || $episod->name) {
                if ($episod->id === $episodeId || $episod->name === $episodeName) {

                    $dateTime = new DateTime($episod->air_date);
                    $newDateTime = new DateTime();

                    if ($comment) {
                        $episodeSentimentValue = $this->sentimentAnalysis->analyze($comment);
                    }

                    $reviews = new Reviews();
                    $reviews->setEpisodeId($episod->id);
                    $reviews->setEpisodeName($episod->name);
                    $reviews->setReleaseDate($dateTime);
                    $reviews->setScore($episodeSentimentValue);
                    $reviews->setEpisodeComment($comment);
                    $reviews->setCreateDate($newDateTime);

                    $this->em->persist($reviews);
                    $this->em->flush();

                    return $reviews->getEpisodeId();
                }
            }
        }

        return null;
    }
}