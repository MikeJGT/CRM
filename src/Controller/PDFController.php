<?php

namespace App\Controller;

use App\Entity\Incident;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pontedilana\PhpWeasyPrint\Pdf;
use Twig\Environment;
use App\Service\PdfManager;

class PDFController extends AbstractController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly Pdf $weasyPrint,
    ) {
    }

    #[Route('/pdf/{id}', name: 'pdf_live')]
    public function renderPdfLive(Incident $incident): Response
    {
        $pdfManager = new PdfManager($this->twig, $this->weasyPrint);

        $pdfFromTemplate='pdf/index.html.twig';
        $templateParams= ['incident' => $incident];

        return $pdfManager->renderPdfLive($incident, $pdfFromTemplate, $templateParams);
    }

    //Ruta para descargar el pdf en Local.
    #[Route('/pdf/local/{id}', name: 'pdf_local')]
    public function savePdfLocal(Incident $incident)
    {
        $pdfManager = new PdfManager($this->twig, $this->weasyPrint);

        $pdfTemplate='pdf/index.html.twig';
        $templateParams= ['incident' => $incident];
        
        //Ruta local + nombre del pdf
        $pdfLocalPath = $this->getParameter('kernel.project_dir') . '/public/incident-'.$incident->getAssigned().'.pdf';
        $pdfManager->savePDFLocal($pdfTemplate,$templateParams,$pdfLocalPath);

        return $this->redirectToRoute('incident');
    }
}