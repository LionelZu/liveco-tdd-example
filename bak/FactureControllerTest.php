<?php

namespace App\Tests\Controller;

use App\Entity\Achat;
use App\Entity\Adherent;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FactureControllerTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->createNativeQuery('DELETE FROM Achat', new ResultSetMapping())->execute();
        $this->entityManager->createNativeQuery('DELETE FROM Adherent', new ResultSetMapping())->execute();
        
        $this->entityManager->flush();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * @test
     */
    public function invoices_shouldReturnCorrectTotal_withAdh1(): void
    {
        // Given
        $adherent = new Adherent();
        $adherent->setId('ADH-1');
        $adherent->setName('Adherent 1');
        $adherent->setCotisation(10);

        $achat = new Achat();
        $achat->setAdherent($adherent);
        $achat->setCodeProduit("produit1");
        $achat->setCurrentPrice("15");
        $achat->setNegociatePrice("10");
        $achat->setQuantity(10);

        $adherent->addAchat($achat);

        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        // When
        $this->client->request('GET', '/invoices', [
            'adherent' => 'ADH-1'
        ]);
        $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString('{
            "currentTotal": 150,
            "negotiateTotal": 100,
            "economy": 50,
            "cotisation": 10,
            "realEconomy": 40
        }', $response->getContent());
    }

    /**
     * @test
     */
    public function invoices_shouldReturnCorrectTotal_withAdh2(): void
    {
        // Given
        $adherent = new Adherent();
        $adherent->setId('ADH-2');
        $adherent->setName('Adherent 2');
        $adherent->setCotisation(100);

        $achat = new Achat();
        $achat->setAdherent($adherent);
        $achat->setCodeProduit("produit1");
        $achat->setCurrentPrice("25");
        $achat->setNegociatePrice("20");
        $achat->setQuantity(5);

        $achat2 = new Achat();
        $achat2->setAdherent($adherent);
        $achat2->setCodeProduit("produit2");
        $achat2->setCurrentPrice("12.5");
        $achat2->setNegociatePrice("10");
        $achat2->setQuantity(10);

        $adherent->addAchat($achat);
        $adherent->addAchat($achat2);

        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        // When
        $this->client->request('GET', '/invoices', [
            'adherent' => 'ADH-2'
        ]);
        $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString('{
            "currentTotal": 250,
            "negotiateTotal": 200,
            "economy": 50,
            "cotisation": 100,
            "realEconomy": -50
        }', $response->getContent());
    }


    /**
     * @test
     */
    public function invoices_shouldReturnCorrectTotal_withDiscountCotisation(): void
    {
        // Given
        $adherent = new Adherent();
        $adherent->setId('ADH-2');
        $adherent->setName('Adherent 2');
        $adherent->setCotisation(100);

        $achat = new Achat();
        $achat->setAdherent($adherent);
        $achat->setCodeProduit("produit1");
        $achat->setCurrentPrice("25");
        $achat->setNegociatePrice("23");
        $achat->setQuantity(100);

        $achat2 = new Achat();
        $achat2->setAdherent($adherent);
        $achat2->setCodeProduit("produit2");
        $achat2->setCurrentPrice("20");
        $achat2->setNegociatePrice("17");
        $achat2->setQuantity(200);

        $adherent->addAchat($achat);
        $adherent->addAchat($achat2);

        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        // When
        $this->client->request('GET', '/invoices', [
            'adherent' => 'ADH-2'
        ]);
        $response = $this->client->getResponse();

        // Then
        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString('{
            "currentTotal": 6500,
            "negotiateTotal": 5700,
            "economy": 800,
            "cotisation": 90,
            "realEconomy": 710
        }', $response->getContent());
    }


    /**
     * @test
     */
    public function invoices_shouldReturnError_whenAdherentIsIncorrect(): void
    {
        // When
        $this->client->request('GET', '/invoices', [
            'adherent' => 'ADH-1'
        ]);

        // Then
        $this->assertResponseStatusCodeSame(404, 'Adherent not found');
    }

    /**
     * @test
     */
    public function invoices_shouldReturnError_whenAdherentIsAbsent(): void
    {
        $this->client->request('GET', '/invoices', []);

        $this->assertResponseStatusCodeSame(400, 'Incorrect parameters');
    }

}
