<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Picture;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * Class HomeController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdvertController extends AbstractController
{
    /**
   * @Route("admin/advert/{id}", name="admin.advert.show")
   */
    public function show(Advert $advert,AdvertRepository $advertRepository, PictureRepository $pictureRepository){
        $pictures = $pictureRepository->findBy(['advert' => $advert]);
        $advert = $advertRepository->findOneBy(['id' => $advert->getId()]);
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
            'pictures' => $pictures
        ]);
    }

    /**
     * @Route("admin/{id}/change-state/{transition}", name="admin.advert.change_state", methods={"GET"})
     */
    public function changeState(Advert $advert, string $transition, WorkflowInterface $advertStateMachine, EntityManagerInterface $manager): Response
    {
        if ($advertStateMachine->can($advert, $transition)) {
            $advertStateMachine->apply($advert, $transition);
            $manager->flush();
            $this->addFlash('success', sprintf(' La transition "%s" a été appliquée .', $transition));
        } else {
            $this->addFlash('error', sprintf('La transition "%s" ne peut pas être appliquée à l\'annonce "%s" .', $transition, $advert->getTitle()));
        }

        return $this->redirectToRoute('admin.adverts.show',array(
            'id' => $advert->getCategory()->getId()
        ));
    }
}
