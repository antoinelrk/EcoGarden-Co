<?php

namespace App\Repository;

use App\Entity\Advice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Advice>
 */
class AdviceRepository extends ServiceEntityRepository
{
    public const int MAX_PAGE = 10;

    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $entityManager,
        private readonly \JMS\Serializer\SerializerInterface $serializer,
    ) {
        parent::__construct($registry, Advice::class);
    }

    public function all(Request $request): array
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', self::MAX_PAGE);

        return $this->createQueryBuilder('b')
            ->setFirstResult(($page - 1) * $limit)
            ->orderBy('b.created_at', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Creates a new advice.
     *
     * @throws JsonException
     */
    public function create(Request $request): string
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $advice = new Advice();
        $advice->setTitle($data['title']);
        $advice->setDescription($data['description']);

        $months = array_map('intval', $data['months']);
        $advice->setMonths($months);

        $advice->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($advice);
        $this->entityManager->flush();

        return $this->serializer->serialize($advice, 'json');
    }

    /**
     * Finds advice by month.
     *
     * @param int $month
     * @return array
     */
    public function findByMonth(int $month): array
    {
        // Use the entity manager to create a native SQL query
        $rsm = new ResultSetMappingBuilder($this->entityManager);

        // Add the entity mapping
        $rsm->addRootEntityFromClassMetadata(Advice::class, 'a');

        // Get the table name from metadata
        $table = $this->getClassMetadata()->getTableName();

        // Define the SQL query
        $sql = <<<SQL
            SELECT a.*
            FROM {$table} a
            WHERE EXISTS (
                SELECT 1
                FROM jsonb_array_elements_text(a.months::jsonb) AS m(val)
                WHERE CASE
                    WHEN m.val ~ '^\s*\d{1,2}\s*$' THEN (m.val)::int = :month
                    ELSE FALSE
                END
            )
        SQL;

        // Create the native query
        $query = $this->entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('month', $month);

        return $query->getResult();
    }
}
