<?php

namespace Test\Controller;

use App\Entity\Achat;
use App\Entity\Adherent;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EtudeControllerTest extends WebTestCase
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
        $this->entityManager->createNativeQuery('DELETE FROM Adherent', new ResultSetMapping() )->execute();

        $this->entityManager->flush();
        
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * @test
     */
    public function shouldReturnOk(): void
    {
        $adherent = new Adherent();
        $adherent->setId('ADH-1');
        $adherent->setName('Adherent');
        $adherent->setCotisation(100);

        $achat = new Achat();
        $achat->setCurrentPrice(10);
        $achat->setQuantity(10);
        $achat->setCodeProduit('PDT1');
        $achat->setNegociatePrice(9);
        $adherent->addAchat($achat);
        
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', '/etude', [
            'adherent' => 'ADH-1'
        ]);
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString('{
            "total": 100,
            "totalNegociate": 90,
            "economy": 10,
            "cotisation": 100,
            "economyReal": -90
        }', $response->getContent());
    }


    /**
     * @test
     */
    public function shouldReturnData_Adherent2(): void
    {
        $adherent = new Adherent();
        $adherent->setId('ADH-2');
        $adherent->setName('Adherent 2');
        $adherent->setCotisation(500);

        $achat = new Achat();
        $achat->setCurrentPrice(100);
        $achat->setQuantity(10);
        $achat->setCodeProduit('PDT1');
        $achat->setNegociatePrice(90);
        $adherent->addAchat($achat);
        
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', '/etude', [
            'adherent' => 'ADH-2'
        ]);
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString('{
            "total": 1000,
            "totalNegociate": 900,
            "economy": 10,
            "cotisation": 500,
            "economyReal": -90
        }', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldReturnData_AdherentWithDiscount(): void
    {
        $adherent = new Adherent();
        $adherent->setId('ADH-2');
        $adherent->setName('Adherent 2');
        $adherent->setCotisation(500);

        $achat = new Achat();
        $achat->setCurrentPrice(100);
        $achat->setQuantity(50);
        $achat->setCodeProduit('PDT1');
        $achat->setNegociatePrice(90);
        $adherent->addAchat($achat);
        
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', '/etude', [
            'adherent' => 'ADH-2'
        ]);
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString('{
            "total": 5000,
            "totalNegociate": 4500,
            "economy": 10,
            "cotisation": 450,
            "economyReal": -90
        }', $response->getContent());
    }

    /**
     * @test
     */
    public function shouldReturnError_whenAdherentIsAbsent(): void
    {
        $crawler = $this->client->request('GET', '/etude', []);

        $this->assertResponseStatusCodeSame(400);
    }

    /**
     * @test
     */
    public function shouldReturnError_whenAdherentIsNotFound(): void
    {
        $crawler = $this->client->request('GET', '/etude', [
            'adherent' => 'ADH-NOTFOUND'
        ]);

        $this->assertResponseStatusCodeSame(404);
    }


}
