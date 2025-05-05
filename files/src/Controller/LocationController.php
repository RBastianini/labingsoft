<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Intent\CreateLocationIntentForm;
use App\Intent\CreateLocationIntent;
use App\IntentHandler\CreateLocationHandler;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/locations')]
class LocationController extends AbstractController
{
    #[Route('/create', name: 'create_location')]
    public function create(Request $request, CreateLocationHandler $createLocationHandler): Response
    {
        $createLocationIntent = new CreateLocationIntent();
        $form = $this->createForm(CreateLocationIntentForm::class, $createLocationIntent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $createLocationHandler->handle($createLocationIntent);

            return $this->redirectToRoute(
                'view_weather_forecasts_by_city',
                ['cityName' => $location->getName(), 'countryCode' => $location->getCountry()]
            );
        }

        return $this->render('location/create.html.twig', ['form' => $form]);
    }

    #[Route('/', name: 'view_all_locations')]
    public function index(
        LocationRepository $locationRepository,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        if ($page <= 0) {
            throw $this->createNotFoundException('Invalid page');
        }
        $pageSize = 5;
        $locations = $locationRepository->findPaginated($page, $pageSize);

        return $this->render(
            'location/index.html.twig',
            [
                'page' => [
                    'number' => $page,
                    'items' => $locations,
                    'size' => $pageSize,
                    'hasNextPage' => count($locations) === $pageSize,
                    'hasPreviousPage' => $page >= 2,
                ],
            ]
        );
    }
}
