<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Enum\Status;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'customer_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();
            //Envoie d'un email en fonction du statut du customer
            $this->sendNotification($customer);

            return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/customer.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer): Response
    {
        $last_status = $customer->getStatus();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //Envoie d'un email en fonction du statut du customer uniquement si changement de statut
            if ($last_status != $form->get('status')->getData())
                $this->sendNotification($customer);


            return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/customer.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }

    private function sendNotification(Customer $customer)
    {
        $customer->setLastEmailAt(new \DateTimeImmutable());
        $this->getDoctrine()->getManager()->flush();
        $email = (new TemplatedEmail())
            ->from(new Address($_ENV['ADMIN_EMAIL'], $_ENV['APP_NAME']))
            ->to(new Address($customer->getEmail(), $customer->getLastname()))
            ->subject($this->getSubjectFromStatus($customer))

            // path of the Twig template to render
            ->htmlTemplate('email/customer/tpl_' . $customer->getStatus() . '.html.twig');

        // pass variables (name => value) to the template

        $this->mailer->send($email);


        return ['code' => 200];
    }

    private function getSubjectFromStatus(Customer $customer){

        $subject = match ($customer->getStatus()) {
            default => "Bienvenue, vous venez d'etre inscrit sur notre logiciel",
            Status::STATUS_PENDING => "Hey, Nous avons commencé à étudier votre contrat",
            Status::STATUS_CANCEL => "Oups, nous venons d'annuler votre contrat",
            Status::STATUS_CONFIRM => "Yeah, nous venons de confirmer votre contrat",
        };

        return $subject;
    }
}
