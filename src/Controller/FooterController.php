<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends AbstractController
{
    #[Route('/faq', name: 'faq')]
    public function faq(): Response
    {
        return $this->render('apropos/faq.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions')]
    public function mentions(): Response
    {
        return $this->render('apropos/mentions.html.twig');
    }

    #[Route('/politique-confidentialite', name: 'pdc')]
    public function pdc(): Response
    {
        return $this->render('apropos/pdc.html.twig');
    }
}
