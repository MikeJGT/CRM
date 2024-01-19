<?php

namespace App\Controller;

use DateTime;
use App\Form\IncidentType;
Use App\Entity\Incident;
use App\Service\FileUploader;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\IncidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

class IncidentController extends AbstractController
{
    #[Route('/', name: 'incident')]
    public function index(IncidentRepository $incidentRepository,
    UserInterface $userInterface ): Response
    {
        $incidents = $incidentRepository->getAllIncidents();
        $userName=$userInterface->getName();
        
        return $this->render('incident/index.html.twig', [
            'incidents' => $incidents,
            'username' => $userName
        ]);
    }

    #[Route('/create', name: 'create_incident')]
    public function createIncident(
        Request $request, 
        UserInterface $userInterface, 
        EntityManagerInterface $em,
        FileUploader $fileUploader
        ): Response
    {
        $incident= new Incident();
        $form = $this->createForm(IncidentType::class, $incident);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form-> isValid()){
            $incident->setAssigned($userInterface->getName());
            $incident->setStartDate(new DateTime());
            $brochureFile = $form->get('brochure')->getData();

            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $incident->setBrochureFilename($brochureFileName);
            }

            $em->persist($incident);
            $em->flush();
            return $this->redirectToRoute('incident');
        }

        return $this->render('incident/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/update/{id}', name: 'update_incident')]
    public function updateIncident(Request $request, Incident $incident, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(IncidentType::class, $incident);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($incident->getState() == 'close'){
                $incident->setFinishDate(new DateTime());
            }else{
                $incident->setFinishDate(null);
            }

            $em->persist($incident);
            $em->flush();
            return $this->redirectToRoute('incident');
        }

        return $this->render('incident/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_incident', methods: ['GET'])]
    public function deleteIncident(EntityManagerInterface $em, Incident $incident): Response
    {   
        $fileSystem = new Filesystem();
        $fileName = $incident->getBrochureFilename();
        $fileSystem->remove('uploads/brochures/' . $fileName);

        $em->remove($incident);
        $em->flush();

        return $this->redirectToRoute('incident');
    }

    #[Route('/download/{id}', name: 'download_incident', methods: ['GET'])]
    public function downloadIncident(Incident $incident): Response
    {
        $fileName=$incident->getBrochureFilename();
    
        return $this->file('uploads/brochures/' . $fileName, 'Incident-' . $incident->getAssigned() . '.pdf');
    }
}
