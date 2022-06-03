<?php
namespace App\Controller\Address;

use App\Service\AddressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AddressApiController extends AbstractController
{
    /**
     * @var AddressService
     */
    private AddressService $addressService;

    /**
     * @var Serializer
     */
    private Serializer $serializer;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getAddressByStreetChunk(Request $request): Response
    {
        $address = \json_decode($request->getContent(), true)['adresse'];
        $addresses = $this->addressService->getAddressByStreetChunk($address);

        $normalizedAddresses = $this->serializer->normalize(
            $addresses,
            null,
            [
                AbstractNormalizer::ATTRIBUTES => ['street', 'postalCode', 'city'],
            ]
        );

        $serializedAddresses = $this->serializer->serialize($normalizedAddresses, 'json');

        $response = new Response($serializedAddresses);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}