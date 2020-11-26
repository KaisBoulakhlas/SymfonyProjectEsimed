<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Form\AdminUserType;
use App\Repository\AdminUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 * @IsGranted("ROLE_ADMIN")
 */
class AdminUserController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/admin/users", name="admin.users")
     */
    public function index(AdminUserRepository $adminUserRepository): Response
    {
        $users = $adminUserRepository->findAll();
        $user = new AdminUser();
        $currentAdmin = $this->getUser()->getId();
        $roles = $user->getRoles();
        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
            'roles' => $roles,
            'currentAdmin' => $currentAdmin
        ]);
    }

    /**
     * @Route("admin/users/create", name="admin.users.add" , methods="GET|POST")
     */
    public function new(Request $request)
    {
        $adminUser = new AdminUser();
        $em = $this->em;

        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($adminUser);
            $em->flush();
            $this->addFlash('success', "L'administrateur'". $adminUser->getUsername() . "a été créé avec succès.");
            return $this->redirectToRoute('admin.users');
        }

        return $this->render('admin_user/add.html.twig', [
            'adminUser' => $adminUser,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/user/edit/{id}", name="admin.user.edit", methods="GET|POST")
     */
    public function edit($id, Request $request,AdminUserRepository $adminUserRepository)
    {
        $em = $this->em;
        $adminUser = $adminUserRepository->find($id);
        $form = $this->createForm(AdminUserType::class, $adminUser,['mandatory' => false]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if ($adminUser->getPlainPassword() !== null){
                $form->getData()->setPassword('');
            }
            $em->flush();
            $this->addFlash('success', "L'administrateur ". $adminUser->getUsername() . " a été modifié avec succès.");
            return $this->redirectToRoute('admin.users');
        };

        return $this->render('admin_user/edit.html.twig', [
            'adminUser' => $adminUser,
            'request' => $request,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("admin/user/delete/{id}", name="admin.user.delete", methods="DELETE")
     */
    public function delete($id, Request $request, AdminUserRepository $adminUserRepository)
    {
        $em = $this->em;
        $adminUser = $adminUserRepository->find($id);
        if($this->isCsrfTokenValid('delete' . $adminUser->getId(), $request->request->get('_token'))){
            $em->remove($adminUser);
            $em->flush();
            $this->addFlash('success', "L'administrateur " . $adminUser->getUsername() . " est supprimé avec succès.");
        }

        return $this->redirectToRoute('admin.users');
    }

}
