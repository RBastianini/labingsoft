<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController
{
    #[Route('/')]
    public function index(Request $request): Response
    {
        return new Response('<html><body>Home page</body></html>');
    }
}