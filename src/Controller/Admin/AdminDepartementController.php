<?php

namespace App\Controller\Admin;
use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/departement', name: 'admin_departement')]
class AdminDepartementController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(DepartementRepository $departementRepository): Response
    {
        $departements = $departementRepository->findAll();
        
        return $this->render('admin/admin_departement/index.html.twig', [
            'departements' => $departements
        ]);
    }


    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    
    public function editer(Request $request, 
                            EntityManagerInterface $entityManagerInterface,  
                            DepartementRepository $departementRepository,
                            int $id = null): Response
    {
        if ($id == null){
            $departement = new Departement();
        }else{
            $departement = $departementRepository->find($id);
        }
        
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);
        // si le form est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
           
            // traitement des données
            $entityManagerInterface->persist($departement);
            $entityManagerInterface->flush();
            
            //messages flash
            $this->addFlash('success', 'Le departement a bien été enregistré');

            return $this->redirectToRoute('admin_departement_lister');
        
        }

        return $this->render('admin/admin_departement/editer.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(EntityManagerInterface $entityManagerInterface,
                                DepartementRepository $departementRepository,
                                int $id): Response
    {
        $departement = $departementRepository->find($id);
        $entityManagerInterface->remove($departement);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_departement_lister');
    }
    
}
