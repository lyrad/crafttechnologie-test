<?php
namespace App\Service;

use App\Repository\Address\AddressRepository;
use App\Util\Address\AddressMonologDBHandler;
use Psr\Log\LoggerInterface;

class AddressService
{
    /**
     * @var AddressRepository
     */
    private AddressRepository $addressRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $addressApiLogger;

    public function __construct(AddressRepository $addressRepository, LoggerInterface $addressApiLogger)
    {
        $this->addressRepository = $addressRepository;
        $this->addressApiLogger = $addressApiLogger;
    }

    /**
     * @param string $search
     * @param string $ip
     * @return Address[]
     */
    public function searchAddress(string $search, string $ip): array
    {
        $this->addressApiLogger->info(
            \sprintf('Search address triggered : "%s"', $search),
            [ 'search' => $search, 'ip' => $ip ]
        );

        return $this->addressRepository->getAddressBySubString($search);
    }
}