<?php


namespace App\Tests\Func;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Picture;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Vich\UploaderBundle\Entity\File;

class PictureTest  extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetPictures() : void
    {
        try {
            $client = $this->createClient()->request('GET', 'api/pictures');
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
        $this->assertMatchesResourceCollectionJsonSchema(Picture::class);
    }

    public function testGetPicture() : void
    {
        try {
            $client = $this->createClient()->request('GET', '/api/pictures/1');
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
        $this->assertMatchesResourceCollectionJsonSchema(Picture::class);
    }
    /*
      public function testPostPicture() : void
        {
            $photo = new UploadedFile(
                'C:\Users\kakin\Documents\myAvatar.jpg',
                'myAvatar.jpg',
                'image/jpeg',
                null
            );
            try {
                $client = $this->createClient()->request('POST', 'api/pictures',  [
                    ['advert' => 1],
                    ['file' => $photo],
                ]);
            } catch (TransportExceptionInterface $e) {
                echo "Error" . $e;
            }

            $this->assertResponseStatusCodeSame(Response::HTTP_CREATED,$client->getStatusCode());
            $this->assertResponseHeaderSame('content-type', 'multipart/form-data');
            $this->assertMatchesResourceCollectionJsonSchema(Picture::class);
        }
     */

}