<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("admin/categories", name="admin.categories")
     */
    public function index(CategoryRepository $categoryRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $datas = $categoryRepository->findAll();
        $categories = $paginator->paginate($datas, $request->query->getInt('page', 1), 30);
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("admin/categories/create", name="admin.categories.add" , methods="GET|POST")
     */
    public function new(Request $request)
    {
        $category = new Category();
        $em = $this->em;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', "La catégorie  ". $category->getName() . "  a été créé avec succès.");
            return $this->redirectToRoute('admin.categories');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/category/edit/{id}", name="admin.category.edit", methods="GET|POST")
     */
    public function edit($id, Request $request,CategoryRepository $categoryRepository)
    {
        $em = $this->em;
        $category = $categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', "La catégorie ". $category->getName() . " a été modifiée avec succès.");
            return $this->redirectToRoute('admin.categories');
        };

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),

        ]);
    }


    /**
     * @Route("admin/category/delete/{id}", name="admin.category.delete", methods="DELETE")
     */
    public function delete($id, Request $request, CategoryRepository $categoryRepository)
    {
        $em = $this->em;
        $category = $categoryRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))){
            $adverts = $category->getAdverts()->count();
            if($adverts === 0){
                $em->remove($category);
                $em->flush();
                $this->addFlash('success', "La catégorie " . $category->getName() . " est supprimée avec succès.");
            } else {
                $this->addFlash('danger', "La catégorie " . $category->getName() . " ne peut pas être supprimée car contient des annonces.");
            }
        }

        return $this->redirectToRoute('admin.categories');
    }
}
