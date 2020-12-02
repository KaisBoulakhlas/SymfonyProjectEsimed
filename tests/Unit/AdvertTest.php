<?php


namespace App\Tests\Unit;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Picture;
use PHPUnit\Framework\TestCase;

class AdvertTest extends TestCase
{
    /**
     * @var Advert
     */
    private Advert $advert;

    protected function setUp() : void
    {
        parent::setUp();
        $this->advert = new Advert();
    }

    public function testGetTitle() : void
    {
        $value = 'test';
        $response = $this->advert->setTitle($value);

        self::assertInstanceOf(Advert::class, $response);
        self::assertEquals($value, $this->advert->getTitle());
    }
    public function testGetContent() : void
    {
        $value = 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttest';
        $response = $this->advert->setContent($value);

        self::assertInstanceOf(Advert::class, $response);
        self::assertEquals($value, $this->advert->getContent());
    }

    public function testGetAuthor() : void
    {
        $value = 'Mr Hugo';
        $response = $this->advert->setAuthor($value);

        self::assertInstanceOf(Advert::class, $response);
        self::assertEquals($value, $this->advert->getAuthor());
    }

    public function testGetEmail() : void
    {
        $value = 'kaisboulakhlas9@gmail.com';
        $response = $this->advert->setEmail($value);

        self::assertInstanceOf(Advert::class, $response);
        self::assertEquals($value, $this->advert->getEmail());
    }

    public function testGetCategory() : void
    {
        $category = new Category();

        $response = $this->advert->setCategory($category);

        self::assertInstanceOf(Advert::class, $response);
        self::assertInstanceOf(Category::class, $category);
        self::assertEquals($category, $this->advert->getCategory());
    }

    public function testGetPrice() : void
    {
        $value = 10000.0;
        $price = $this->advert->setPrice($value);

        self::assertInstanceOf(Advert::class, $price);
        self::assertIsFloat($value);
        self::assertEquals($value, $this->advert->getPrice());
    }

    public function testGetCreatedAt() : void
    {
        $date = new \DateTimeImmutable();
        $response = $this->advert->setCreatedAt($date);

        self::assertInstanceOf(Advert::class, $response);
        self::assertEquals($date, $this->advert->getCreatedAt());
    }

    public function testGetPublishedAt() : void
    {
        $date = new \DateTimeImmutable();
        $response = $this->advert->setPublishedAt($date);

        self::assertInstanceOf(Advert::class, $response);
        self::assertEquals($date, $this->advert->getPublishedAt());
    }

    public function testGetPicture() : void
    {
        $picture = new Picture();
        $picture2 = new Picture();

        // Test ajout d'images
        $this->advert->addPicture($picture);
        $this->advert->addPicture($picture2);

        self::assertInstanceOf(Advert::class, $this->advert->addPicture($picture));
        self::assertInstanceOf(Advert::class,$this->advert->addPicture($picture2));
        self::assertCount(2,$this->advert->getPictures());
        self::assertTrue($this->advert->getPictures()->contains($picture));
        self::assertTrue($this->advert->getPictures()->contains($picture2));

        // Test suppression d'images
        $response = $this->advert->removePicture($picture);

        self::assertInstanceOf(Advert::class,$response);
        self::assertCount(1,$this->advert->getPictures());
        self::assertFalse($this->advert->getPictures()->contains($picture));
        self::assertTrue($this->advert->getPictures()->contains($picture2));
    }


}