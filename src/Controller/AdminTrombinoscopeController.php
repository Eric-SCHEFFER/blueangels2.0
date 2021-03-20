<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\MembresAsso;

class AdminTrombinoscopeController extends AbstractController
{
    /**
     * @Route("/admin/trombinoscope", name="admin.trombinoscope")
     */
    public function index(): Response
    {
        
        return $this->render('admin/trombinoscope/adminTrombinoscope.html.twig', [
            
        ]);
    }
}
