<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MembresAsso;
use App\Repository\MembresAssoRepository;

class AdminTrombinoscopeController extends AbstractController
{
    public function __construct(
        // MembresAssoRepository $membresAssoRepository,
        EntityManagerInterface $em
    ) {
        // $this->membresAssoRepository = $membresAssoRepository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/trombinoscope", name="admin.trombinoscope", methods={"GET"})
     */
    public function loadMembresAsso(MembresAssoRepository $membresAssoRepository): Response
    {
        $membresAsso = $membresAssoRepository->findBy([], ['prenom' => 'DESC']);
        return $this->render('admin/trombinoscope/adminTrombinoscope.html.twig', [
            'membresAsso' => $membresAsso,
        ]);
    }
}
