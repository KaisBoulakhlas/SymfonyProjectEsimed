<?php


namespace App\Tests\Func;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Category;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CategoryTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetCategories() : void
    {
        try {
            $client = $this->createClient()->request('GET', 'api/categories');
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
        $this->assertMatchesResourceCollectionJsonSchema(Category::class);
    }

    public function testGetCategory() : void
    {
        try {
            $client = $this->createClient()->request('GET', '/api/categories/2');
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
        $this->assertMatchesResourceCollectionJsonSchema(Category::class);
    }
}