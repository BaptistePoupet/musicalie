<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/festival', name: 'festival')]
class FestivalController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(FestivalRepository $festivalRepository, DepartementRepository $departementRepository): Response
    {
        return $this->render('index.html.twig', [
            'festivals' => $festivalRepository->findAll(),
            'departements' => $departementRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: '_voir', requirements: ['id' => '\d+'])]
    public function voir(Festival $festival): Response
    {
        return $this->render('festival/voir.html.twig', [
            'festival' => $festival,
        ]);
    }
}
