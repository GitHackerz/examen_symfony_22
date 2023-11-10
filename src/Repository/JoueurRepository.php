<?php

namespace App\Repository;

use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Joueur>
 *
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    public function findAllByName(): array
    {
        return $this->createQueryBuilder('j')
            ->orderBy('j.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function showJoueurs(): array
    {
        return $this->createQueryBuilder('j')
            ->where("j.equipe = :equipe")
            ->setParameter('equipe', 'Tunisie')
            ->orderBy('j.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getMoyenneVote($id): float
    {
        return $this->createQueryBuilder('j')
            ->select('j.moyenneVote')
            ->where("j.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }


//    /**
//     * @return Joueur[] Returns an array of Joueur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Joueur
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
