<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOptionsSiteController extends AbstractController
{
    /**
     * @Route("/admin/options/site", name="admin.options.site")
     */
    public function index(): Response
    {
        return $this->render('admin/optionsSite/optionsSite.html.twig', [
            'controller_name' => 'AdminOptionsSiteController',
        ]);
    }
}
