<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'user_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*
            * On recupere la valeur normalisé du ROLE pour l'enregistrer en array
            */
            $theRole = (is_array($form->get('roles')->getNormData())) ? $form->get('roles')->getNormData() : [$form->get('roles')->getNormData()];

            $user->setRoles($theRole);


            /*
            * Si le mot de passe du user est vide on lu genere un mot de passe sinon on prend celui renseigné
            */

            $passwordGet = (empty($form->get('passwordForce')->getData())) ? 'RTX45GP12' : $form->get('passwordForce')->getData();

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $passwordGet
                )
            );


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/user.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET','POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           /*
           * On recupere la valeur normalisé du ROLE pour l'enregistrer en array
           */
            $theRole = (is_array($form->get('roles')->getNormData())) ? $form->get('roles')->getNormData() : [$form->get('roles')->getNormData()];

            $user->setRoles($theRole);

            /*
            * On regenere le mot de passe seulement si un nouveau est renseigné
            */
            if (!empty($form->get('password')->getData()) && empty($form->get('passwordForce')->getData())) {
                $user->setPassword(
                    $form->get('password')->getData()
                );
            } else {
                $user->setPassword(
                    $this->passwordEncoder->encodePassword($user, $form->get('passwordForce')->getData())
                );
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/user.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
