<?php

namespace App\Controller;
use App\Entity\Departement;
use App\Repository\FestivalRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/departement', name: 'departement')]
class DepartementController extends AbstractController
{
    #[Route('/departement', name: '_index')]
    public function index(): Response
    {
        return $this->render('departement/index.html.twig', [
            'controller_name' => 'DepartementController',
        ]);
    }
    #[Route('/{id}', name: '_voir')]
    public function voir(Departement $departement,
                    FestivalRepository $festivalRepository
                    ): Response
    {
        return $this->render('departement/voir.html.twig', [
            'departement' => $departement,
            'festivals' => $festivalRepository->findBy(['departement' => $departement])

        ]);
    }
}
