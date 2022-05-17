<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/edit/{id}", name="app_user", methods={"GET", "POST"})
     */
    public function index(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);
        // $user->getPassword();

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            $this->addFlash(
                'Success',
                'Changes saved !'
            );
            return $this->redirectToRoute('app_user', ["id" => $user->getId()]);
        }

        return $this->renderForm('user/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
