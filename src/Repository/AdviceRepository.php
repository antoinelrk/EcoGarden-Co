<?php

namespace App\Repository;

use App\Entity\Advice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
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

//    /**
//     * @return Advice[] Returns an array of Advice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Advice
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
