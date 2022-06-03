<?php
namespace App\DataFixture;

use App\Entity\Address\Address;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixture extends Fixture
{
    const JSON_SOURCE = '[
        {"adresse":"Impasse des Cocotiers","codePostal":"97430", "ville":"LE TAMPON"},
        {"adresse":"Impasse des Cocotiers","codePostal":"97429","ville":"PETITE-\u00ceLE"},
        {"adresse":"Impasse des Cocotiers","codePostal":"97439","ville":"SAINTE-ROSE"},
        {"adresse":"Rue des Cocotiers","codePostal":"97436","ville":"SAINT-LEU"},
        {"adresse":"Rue des Cocotiers","codePostal":"97470","ville":"SAINT-BENO\u00ceT"},
        {"adresse":"Rue des Cocotiers","codePostal":"97419","ville":"LA POSSESSION"},
        {"adresse":"Rue des Cocotiers","codePostal":"97412","ville":"BRAS-PANON"},
        {"adresse":"Rue des Cocotiers","codePostal":"97434","ville":"SAINT-PAUL"},
        {"adresse":"All\u00e9e des Cocotiers","codePostal":"97434","ville":"SAINT-PAUL"},
        {"adresse":"Allee des Cocotiers","codePostal":"97438","ville":"SAINTE-MARIE"},
        {"adresse":"All\u00e9e des Cocotiers","codePostal":"97480","ville":"SAINT-JOSEPH"},
        {"adresse":"All\u00e9e des Cocotiers","codePostal":"97400","ville":"SAINT-DENIS"},
        {"adresse":"Allee des Cocotiers","codePostal":"97410","ville":"SAINT-PIERRE"},
        {"adresse":"Allee des Cocotiers","codePostal":"97427","ville":"L\u0027\u00c9TANG-SAL\u00c9"},
        {"adresse":"Allee des Cocotiers","codePostal":"97441","ville":"SAINTE-SUZANNE"}
    ]';

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $sourceData = \json_decode(self::JSON_SOURCE, true);

        foreach($sourceData as $addressArray)
        {
            $address = new Address();
            $address->setStreet($addressArray['adresse']);
            $address->setPostalCode($addressArray['codePostal']);
            $address->setCity($addressArray['ville']);

            $manager->persist($address);
        }

        $manager->flush();
    }
}

