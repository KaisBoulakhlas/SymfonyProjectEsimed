<?php


namespace App\Tests\Unit;


use App\Entity\Advert;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @var Category
     */
    private Category $category;

    protected function setUp() : void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testGetName() : void
    {
        $value = 'test';
        $response = $this->category->setName($value);

        self::assertInstanceOf(Category::class, $response);
        self::assertEquals($value, $this->category->getName());
    }

    public function testGetAdvert() : void
    {
        $advert = new Advert();
        $advert2 = new Advert();

        // Test ajout d'annonces
        $this->category->addAdvert($advert);
        $this->category->addAdvert($advert2);

        self::assertInstanceOf(Category::class, $this->category->addAdvert($advert));
        self::assertInstanceOf(Category::class,$this->category->addAdvert($advert2));
        self::assertCount(2,$this->category->getAdverts());
        self::assertTrue($this->category->getAdverts()->contains($advert));
        self::assertTrue($this->category->getAdverts()->contains($advert2));

        // Test suppression d'annonce
        $response = $this->category->removeAdvert($advert);

        self::assertInstanceOf(Category::class,$response);
        self::assertCount(1,$this->category->getAdverts());
        self::assertFalse($this->category->getAdverts()->contains($advert));
        self::assertTrue($this->category->getAdverts()->contains($advert2));
    }
}