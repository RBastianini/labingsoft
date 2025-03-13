<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutController extends AbstractController
{
    #[Route('/about/{subject}', name: 'about')]
    public function index(?string $subject = null): Response
    {
        return $this->render('about/index.html.twig', [
            'subject' => $subject,
        ]);
    }
}
