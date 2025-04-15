<?php

namespace App\Controller;


use App\Entity\Reviews;
use App\Repository\ReviewsRepository;
use App\UseCase\CreateRewRickMortyUseCaseInterface;
use Doctrine\ORM\EntityManagerInterface;
use NickBeen\RickAndMortyPhpApi\Exceptions\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RickMortyController extends AbstractController
{

    public function __construct(
        private readonly CreateRewRickMortyUseCaseInterface $createRewRickMortyUseCase,
        private readonly ReviewsRepository                  $reviewsRepository,
        private readonly EntityManagerInterface             $em,
    )
    {
    }


    /**
     * @Annotations\post("/api/get-episode-rick-morty")
     */
    #[Route('api/create-episode', name: 'app_rickmorty')]
    public function createEpisode(Request $request): JsonResponse
    {
        $episode = $request->getContent();
        $queryParams = json_decode($episode, true);

        $episodeComment = $this->createRewRickMortyUseCase->handle($request, $queryParams);

        if ($episodeComment) {
            return new JsonResponse('Comment created for episode ' . $episodeComment);
        } else {
            throw  $this->createNotFoundException('Episode not found');
        }

    }

    /**
     * @Annotations\Get("/api/get-episode-rick-morty")
     * @Operation(
     *     tags={"Export"},
     *     @Parameter(name="name", in="query", required=false),
     *     @Parameter(name="id", in="query", required=false),
     *     @OA\Response(
     *         response="200",
     *         description="Returned when successful"
     *     )
     * )
     */
    #[Route('api/get-episode-rick-morty', name: 'app_rick_morty')]
    public function getEpisode(Request $request): JsonResponse
    {
        if ($id = (int)$request->get('id')) {
            $result = $this->reviewsRepository->findEpisodeReviewsLastThree($id);
        }

        if ($name = (string)$request->get('name')) {
            $result = $this->reviewsRepository->findEpisodeReviewsLastThree($name);
        }

        return new JsonResponse(['reviews' => $result]);
    }
}
