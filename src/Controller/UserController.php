<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private MailerInterface $mailer;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
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

            $this->sendAccess($user, $passwordGet);


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


    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $last_email = $user->getEmail();
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
            $password_changed = 0;
            $passwordGet = null;
            if (!empty($form->get('password')->getData()) && empty($form->get('passwordForce')->getData())) {
                $user->setPassword(
                    $form->get('password')->getData()
                );
            } else {
                $user->setPassword(
                    $this->passwordEncoder->encodePassword($user, $form->get('passwordForce')->getData())
                );
                $password_changed = 1;
                $passwordGet = $form->get('passwordForce')->getData();
            }
            /*
             * On renvoie l'email si le mdp ou email a changé
            */
            if ($last_email != $form->get('email')->getData() || $password_changed == 1) {
                $this->sendAccess($user, $passwordGet);
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
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    private function sendAccess(User $user, $pass=null)
    {

        $email = (new TemplatedEmail())
            ->from(new Address($_ENV['ADMIN_EMAIL'], $_ENV['APP_NAME']))
            ->to(new Address($user->getEmail(), $user->getName()))
            ->subject('Bienvenue sur notre application')

            // path of the Twig template to render
            ->htmlTemplate('email/user/access.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'id' => $user,
                'pass' => $pass,
                'LINK' => $this->generateUrl('home', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);
        $this->mailer->send($email);


        return ['code' => 200];
    }
}
