<?php


namespace App\Tests\Unit;


use App\Entity\Advert;
use App\Entity\Picture;
use PHPUnit\Framework\TestCase;

class PictureTest extends TestCase
{
    /**
     * @var Picture
     */
    private Picture $picture;

    protected function setUp() : void
    {
        parent::setUp();
        $this->picture = new Picture();
    }

    public function testGetAdvert() : void
    {
        $advert = new Advert();

        $response = $this->picture->setAdvert($advert);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($advert, $this->picture->getAdvert());
    }

    public function testGetCreatedAt() : void
    {
        $date = new \DateTimeImmutable();
        $response = $this->picture->setCreatedAt($date);

        self::assertInstanceOf(Picture::class, $response);
        self::assertEquals($date, $this->picture->getCreatedAt());
    }
}