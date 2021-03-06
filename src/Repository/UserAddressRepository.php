<?php

namespace App\Repository;

use App\Entity\UserAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserAddressRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAddressRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAddressRepository[]    findAll()
 * @method UserAddressRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAddressRepository::class);
    }
}
