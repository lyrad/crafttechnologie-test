<?php
namespace App\Tests\Address;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressApiControllerTest extends WebTestCase
{
    /**
     * @const
     */
    const HTTP_STATUS_BAD_REQUEST = 400;

    /**
     * @const
     */
    const HTTP_STATUS_OK = 200;

    /**
     * @return void
     */
    public function testInputValidation(): void
    {
        $client = static::createClient();

        // Missing payload.
        $client->xmlHttpRequest('POST', '/api/adresse');
        $this->assertResponseStatusCodeSame(self::HTTP_STATUS_BAD_REQUEST);

        // Invalid JSON.
        $client->xmlHttpRequest('POST', '/api/adresse', [], [], [], ' "adresse" : "rue" }');
        $this->assertResponseStatusCodeSame(self::HTTP_STATUS_BAD_REQUEST);

        // Missing index in JSON object.
        $client->xmlHttpRequest('POST', '/api/adresse', [], [], [], '{ "noAdress" : "rue" }');
        $this->assertResponseStatusCodeSame(self::HTTP_STATUS_BAD_REQUEST);

        // Search value not a string.
        $client->xmlHttpRequest('POST', '/api/adresse', [], [], [], '{ "noAdress" : { "some" : "oject" } }');
        $this->assertResponseStatusCodeSame(self::HTTP_STATUS_BAD_REQUEST);

        // Test some special chars.
        $client->xmlHttpRequest('POST', '/api/adresse', [], [], [], '{ "adresse" : "àçé#èù&" }');
        $this->assertResponseStatusCodeSame(self::HTTP_STATUS_OK);
    }

    /**
     * We assume test database was created, and fed with fixture App/DataFixture/AppFixture.
     * @return void
     */
    public function testSearchResult(): void
    {
        // Test data set, "search string"/"Expected returned records".
        $testDataSet = [
            '974' => 15,
            '97434' => 2,
            'rue' => 5,
            'allée' => 7,
            'impasse' => 3,
            'saint-paul' => 2,
            'l\'étang-salé' => 1,
            'saint-benoît' => 1,
            'nouméa' => 0,
        ];

        $client = static::createClient();

        foreach($testDataSet as $search => $expectedResultCount)
        {
            $client->xmlHttpRequest(
                'POST',
                '/api/adresse',
                [],
                [],
                [],
                \sprintf('{ "adresse" : "%s" }', $search)
            );

            // Check status code, result is JSON and expected result count match the number of records returned.
            $responseContent = \json_decode($client->getResponse()->getContent(), true);

            $this->assertResponseStatusCodeSame(self::HTTP_STATUS_OK);
            $this->assertNotNull($responseContent);
            $this->assertEquals($expectedResultCount, \count($responseContent));
        }
    }
}
