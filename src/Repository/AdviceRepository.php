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
        // Pattern to match the month in the JSON array
        $pattern = sprintf('(^|[^0-9])%d([^0-9]|$)', $month);

        // Encode the pattern as a JSON array
        $needle = json_encode([$pattern]);

        // Use native SQL query to leverage PostgreSQL's JSONB capabilities
        $em = $this->getEntityManager();

        // ResultSetMappingBuilder to map the results to the Advice entity
        $rsm = new ResultSetMappingBuilder($em);

        // Add the entity mapping
        $rsm->addRootEntityFromClassMetadata(Advice::class, 'a');

        // Get the table name from metadata
        $table = $this->getClassMetadata()->getTableName();

        // Native SQL query using the @> operator to check if the months JSONB contains the specified month
        $sql = <<<SQL
                SELECT a.*
                FROM {$table} a
                WHERE (a.months::jsonb @> :needle::jsonb)
                SQL;

        // Create the native query
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('needle', $needle);

        // Execute the query and return the results
        return $query->getResult();
    }
}
