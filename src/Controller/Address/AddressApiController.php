<?php
namespace App\Controller\Address;

use App\Service\AddressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AddressApiController extends AbstractController
{
    /**
     * @const int Should be available in some symfony core class but couldn't find...
     */
    const HTTP_STATUS_BAD_REQUEST = 400;

    /**
     * @const
     */
    const HTTP_STATUS_OK = 200;

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
     * @return JsonResponse
     */
    public function searchAddress(Request $request): JsonResponse
    {
        // Decode JSON POST request body content.
        $search = \json_decode($request->getContent(), true);

        // Un commentaire

        // Validate inputs (not the right place to do this...).
        // If JSON not valid or missing object key, return a 400 bad request.
        // Normally in an API context, should throw an exception that be caught and turned to JSON by error handler.
        if(
            $search === null ||
            isset($search['adresse']) === false ||
            \is_string($search['adresse']) === false
        ) {
            return new JsonResponse(
                'Input validation error. Expect POST with BODY JSON {"adresse":"..."}.',
                self::HTTP_STATUS_BAD_REQUEST
            );
        }

        $clientIp = $request->getClientIp();
        $addresses = $this->addressService->searchAddress($search['adresse'], $clientIp);

        // Normalize collection of addresses objects.
        // Not totally conform to specs outputs, should create a dedicated normalizer for that...
        $normalizedAddresses = $this->serializer->normalize(
            $addresses,
            null,
            [
                AbstractNormalizer::ATTRIBUTES => ['street', 'postalCode', 'city'],
            ]
        );

        // Serialize normalized collection of addresses objects to JSON.
        $serializedAddresses = $this->serializer->serialize($normalizedAddresses, 'json');

        // Create and return HTTP JSON response.
        return new JsonResponse($serializedAddresses, self::HTTP_STATUS_OK, [], true);
    }
}