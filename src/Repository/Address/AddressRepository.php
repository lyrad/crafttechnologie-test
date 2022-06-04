<?php
namespace App\Repository\Address;

use App\Entity\Address\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AddressRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    /**
     * @param string $streetChuck
     * @return Address[]
     */
    public function getAddressBySubString(string $search): array
    {
        $qb = $this->createQueryBuilder('a');
        return $qb->where($qb->expr()->like('CONCAT(a.street, \' \', a.postalCode, \' \', a.city)', ':search'))
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }
}