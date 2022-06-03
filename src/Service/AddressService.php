<?php
namespace App\Service;

use App\Repository\Address\AddressRepository;
use Doctrine\Common\Collections\Collection;

class AddressService
{
    /**
     * @var AddressRepository
     */
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param string $chunk
     * @return Collection
     */
    public function getAddressByStreetChunk(string $chunk): array
    {
        return $this->addressRepository->getAddressByStreetChunk($chunk);
    }
}