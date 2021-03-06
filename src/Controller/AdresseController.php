<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InfosEtAdressesRepository;

class AdresseController extends AbstractController
{
    public function __construct(InfosEtAdressesRepository $infosEtAdressesRepository)
    {
        $this->infosEtAdressesRepository = $infosEtAdressesRepository;
    }

    /**
     * @Route("/adresse", name="adresse")
     */
    public function index(): Response
    {
        $infos = $this->infosEtAdressesRepository->findOneBy([]);
        return $this->render('adresse/adresse.html.twig', [
            'infos' => $infos,
        ]);
    }
}
