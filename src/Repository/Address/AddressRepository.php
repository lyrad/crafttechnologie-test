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
     * @return Address
     */
    public function getAddressByStreetChunk(string $streetChuck): array
    {
        return $this->findAll();
    }
}