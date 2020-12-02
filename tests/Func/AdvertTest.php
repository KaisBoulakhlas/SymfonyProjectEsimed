<?php


namespace App\Tests\Func;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Advert;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AdvertTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetAdverts() : void
    {
        try {
            $client = $this->createClient()->request('GET', 'api/adverts');
        } catch (TransportExceptionInterface $e) {
            echo "Error" . $e;
        }

        $responseJSON = $client->getContent();

        $responseDecoded = json_decode($client->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJson($responseJSON);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK,$responseJSON);
        $this->assertNotEmpty($responseDecoded);
        $this->assertCount(5, $client->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Advert::class);
    }

    public function testGetAdvert() : void
    {
        try {
            $client = $this->createClient()->request('GET', 'api/adverts/1');
        } catch (TransportExceptionInterface $e) {
            echo "Error" . $e;
        }

        $responseJSON = $client->getContent();

        $responseDecoded = json_decode($client->getContent());
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJson($responseJSON);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK,$responseJSON);
        $this->assertNotEmpty($responseDecoded);
        $this->assertMatchesResourceCollectionJsonSchema(Advert::class);
    }

    public function testPostAdvert() : void
    {
        try {
            $client = $this->createClient()->request('POST', 'api/adverts', ['json' => [
                'title' => 'Test',
                'content' => 'The Handmaid\'s Tale',
                'author' => 'Mr Hugo',
                'email' => 'kaisboulakhlas9@gmail.com',
                'category' => '/api/categories/1',
                'price' => 10000,
            ]]);
        } catch (TransportExceptionInterface $e) {
            echo "Error" . $e;
        }

        $responseJSON = $client->getContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED,$responseJSON);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(Advert::class);
    }
}