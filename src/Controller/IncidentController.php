<?php

namespace App\Controller;

use App\Form\IncidentType;
Use App\Entity\Incident;
use App\Service\FileUploader;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\IncidentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;

class IncidentController extends AbstractController
{
    #[Route('/incident', name: 'incident')]
    public function index(IncidentRepository $incidentRepository): Response
    {

        $incidents = $incidentRepository->getAllIncidents();
        // dd($incidents);
        return $this->render('incident/index.html.twig', [
            'incidents' => $incidents,
        ]);
    }

    #[Route('/incident/create', name: 'create_incident')]
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
        // $user->getName();
        // dd($user->getName());

        // dd($userInterface->getName());
        if($form->isSubmitted() && $form-> isValid()){
            $form->getData();
            $incident->setAssigned($userInterface->getName());
            $incident->setStartDate(new DateTime());
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $incident->setBrochureFilename($brochureFileName);
            }

            // dd($incident);
            $em->persist($incident);
            $em->flush();
            return $this->redirectToRoute('incident');
        }

        return $this->render('incident/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/incident/update/{id}', name: 'update_incident')]
    public function updateIncident(Request $request, Incident $incident, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(IncidentType::class, $incident);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($incident->getState() == 'close'){
                // dd($incident->getState());
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

    #[Route('/incident/delete/{id}', name: 'delete_incident')]
    public function deleteIncident(EntityManagerInterface $em, Incident $incident): Response
    {
        $em->remove($incident);
        $em->flush();
        // $incidents = $incidentRepository->getAllIncidents();

        return $this->redirectToRoute('incident');
    }

    #[Route('/incident/download/{id}', name: 'download_incident')]
    public function downloadIncident(Incident $incident): Response
    {
        $fileName=$incident->getBrochureFilename();

        // $incidents = $incidentRepository->getAllIncidents();

        return $this->file('uploads/brochures/' . $fileName, 'Incident'. '-' . $incident->getAssigned() .'.pdf');
    }
}
