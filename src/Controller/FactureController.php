<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Knp\Snappy\Pdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/facture')]
class FactureController extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($facture);


            $ls_detail = $facture->getFactureDetails();
            foreach ($ls_detail as $item) {
                $item->setFacture($facture);
                $total_line = $item->getPU() * $item->getQtt();
                if (!is_null($item->getTva()))
                    $item->setMontantTva(($total_line * $item->getTva()->getTva()) - $total_line);
                else
                    $item->setMontantTva(0);
                $entityManager->flush();
            }

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/send', name: 'facture_mail_send')]
    public function facture_send(Facture $id)
    {

        $keyword = "facture";
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/files/' . $keyword;
        $html_file_dir = $publicDirectory . '/' . $id->getId() . '/';
        $facture_path = $html_file_dir . $keyword . '_' . $id->getId() . '.pdf';

        if (!file_exists($facture_path)) {
            $this->forward('App\Controller\FactureController::facturePdf', ['id' => $id->getId()]);
        }

        $email = (new TemplatedEmail())
            ->from(new Address($_ENV['ADMIN_EMAIL'], $_ENV['APP_NAME']))
            ->to(new Address($this->getUser()->getEmail(), $this->getUser()->getName()))
            ->subject('Nouvelle Facture #' . $id->getId())
            ->attachFromPath($facture_path, 'Facture LAYAN N° '.$id->getId())
            // path of the Twig template to render
            ->htmlTemplate('email/facture/index.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'facture' => $id,
            ]);
        $mailer_res = $this->mailer->send($email);


        return new JsonResponse([
            'reponse_mail' => $mailer_res,
        ]);


    }

    #[Route('/{id}/edit', name: 'facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ls_detail = $facture->getFactureDetails();
            foreach ($ls_detail as $item) {
                $item->setFacture($facture);
                $total_line = $item->getPU() * $item->getQtt();
                if (!is_null($item->getTva()))
                    $item->setMontantTva(($total_line * $item->getTva()->getTva()) - $total_line);
                else
                    $item->setMontantTva(0);
                $this->getDoctrine()->getManager()->flush();

            }
            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/facturePdf/{id}', name: 'facturePdf')]
    public function facturePdf(Facture $id, Pdf $pdf)
    {
        $keyword = 'facture';
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/files/' . $keyword;
        $html_file_dir = $publicDirectory . '/' . $id->getId() . '/';
        $name_of_pdf = $html_file_dir . $keyword . '_' . $id->getId() . '.pdf';

        @mkdir($html_file_dir, 0777, true);
        @chmod($html_file_dir, 0777);

        $html = $this->renderView('pdf/facture/index.html.twig',
            ['controller_name' => 'FacturePdfController',
                'facture' => $id]
        );
//        echo $html ; exit ;
        $pdf->setOption('margin-bottom', "15mm");
        $pdf->setOption('margin-left', "5mm");
        $pdf->setOption('margin-right', "5mm");
        $pdf->setOption('margin-top', "5mm");

        $pdf_doc = $pdf->getOutputFromHtml($html);

        file_put_contents($name_of_pdf, $pdf_doc);

        return new BinaryFileResponse($html_file_dir . $keyword . '_' . $id->getId() . '.pdf');

    }


}
