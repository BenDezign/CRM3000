<?php

namespace App\Controller;

use App\Entity\Facture;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    private Pdf $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    #[Route('/facture/{id}/pdf', name: 'facture_pdf')]
    public function document_pdf(Facture $id): Response
    {
        return $this->makePdf('facture', $id);
    }

    private function makePdf($keyword, $id): Response
    {
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/files/' . $keyword;
        $html_file_dir = $publicDirectory . '/' . $id->getId() . '/';
        $name_of_pdf = $html_file_dir . $keyword . '_' . $id->getId() . '.pdf';

        @mkdir($html_file_dir, 0777, true);
        @chmod($html_file_dir, 0777);
        $html = $this->renderView('pdf/' . $keyword . '/index.html.twig',
            ['controller_name' => 'FacturePdfController',
                $keyword => $id]
        );
//        echo $html ; exit ;
        $this->pdf->setOption('margin-bottom', "15mm");
        $this->pdf->setOption('margin-left', "5mm");
        $this->pdf->setOption('margin-right', "5mm");
        $this->pdf->setOption('margin-top', "5mm");

        $pdf_doc = $this->pdf->getOutputFromHtml($html);

        file_put_contents($name_of_pdf, $pdf_doc);

        return new BinaryFileResponse($html_file_dir . $keyword . '_' . $id->getId() . '.pdf');
    }
}
