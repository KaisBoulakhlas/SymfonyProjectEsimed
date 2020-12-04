<?php


namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreatePictureAction
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(Request $request): Picture
    {
        $uploadedFile = $request->files->get('file');
        $advertId     = (int)$request->get('advert');


        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }


        $picture = new Picture();
        $picture->setFile($uploadedFile);
        
        if($advertId){
            $advert = $this->manager->getRepository(Advert::class)->find($advertId);
            $picture->setAdvert($advert);
        }

        return $picture;
    }
}