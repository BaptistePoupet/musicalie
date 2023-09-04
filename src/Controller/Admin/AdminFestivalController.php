<?php

namespace App\Controller\Admin;
use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/festival', name: 'admin_festival')]
class AdminFestivalController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function lister(FestivalRepository $festivalRepository): Response
    {
        $festivals = $festivalRepository->findAll();
        
        return $this->render('admin/admin_festival/index.html.twig', [
            'festivals' => $festivals
        ]);
    }


    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifier/{id}', name: '_modifier')]
    
    public function editer(Request $request, 
                            EntityManagerInterface $entityManagerInterface,  
                            FestivalRepository $festivalRepository,
                            int $id = null): Response
    {
        if ($id == null){
            $festival = new Festival();
            $festival->setDateCreation(new \DateTime());
        }else{
            $festival = $festivalRepository->find($id);
        }
        
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);
        // si le form est soumis et valide
        if($form->isSubmitted() && $form->isValid()){
           
            // traitement des données
            $entityManagerInterface->persist($festival);
            $entityManagerInterface->flush();
            
            //messages flash
            $this->addFlash('success', 'Le festival a bien été enregistré');

            return $this->redirectToRoute('admin_festival_lister');
        
        }

        return $this->render('admin/admin_festival/editer.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(EntityManagerInterface $entityManagerInterface,
                                FestivalRepository $festivalRepository,
                                int $id): Response
    {
        $festival = $festivalRepository->find($id);
        $entityManagerInterface->remove($festival);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_festival_lister');
    }
    
}
