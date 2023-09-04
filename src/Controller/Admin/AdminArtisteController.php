<?php

namespace App\Controller\Admin;
use App\Entity\Artiste;
use App\Form\ArtisteType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/artiste', name: 'admin_artiste')]
class AdminArtisteController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(ArtisteRepository $artisteRepository): Response
    {
        $artistes = $artisteRepository->findAll();
        
        return $this->render('admin/admin_artiste/index.html.twig', [
            'artistes' => $artistes
        ]);
    }


    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    
    public function editer(Request $request, 
                            EntityManagerInterface $entityManagerInterface,  
                            ArtisteRepository $artisteRepository,
                            int $id = null): Response
    {
        if ($id == null){
            $artiste = new Artiste();
        }else{
            $artiste = $artisteRepository->find($id);
        }
        
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);
        // si le form est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
           
            // traitement des données
            $entityManagerInterface->persist($artiste);
            $entityManagerInterface->flush();
            
            //messages flash
            $this->addFlash('success', 'Le artiste a bien été enregistré');

            return $this->redirectToRoute('admin_artiste_lister');
        
        }

        return $this->render('admin/admin_artiste/editer.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(EntityManagerInterface $entityManagerInterface,
                                ArtisteRepository $artisteRepository,
                                int $id): Response
    {
        $artiste = $artisteRepository->find($id);
        $entityManagerInterface->remove($artiste);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_artiste_lister');
    }
    
}
