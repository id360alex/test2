<?php

namespace App\Repository;

use App\Entity\Reviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reviews>
 */
class ReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reviews::class);
    }


    public function findEpisodeReviewsLastThree($value): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('episode_id', 'episodeId');
        $rsm->addScalarResult('episode_name', 'episodeName');
        $rsm->addScalarResult('category_avg_price', 'category_avg_price');
        $rsm->addScalarResult('release_date', 'releaseDate');
        $rsm->addScalarResult('episode_comment', 'episodeComment');
        $rsm->addScalarResult('create_date', 'createDate');

        if (gettype($value) === 'string') {
            $where = "episode_name = $value";
        } else if (gettype($value) === 'integer') {
            $where = "episode_id = $value";
        }

        $sql = "
                SELECT 
                    id,
                    episode_name,
                    episode_id,
                    release_date,
                    episode_comment,
                    create_date,
               AVG(score)  OVER (PARTITION BY episode_name) AS category_avg_price
                FROM reviews
                WHERE $where
                order by create_date desc
                limit 3
                ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('episodeId', $value);

        return $query->getResult();
    }
}
