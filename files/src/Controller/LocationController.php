<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Intent\CreateLocationIntentForm;
use App\Form\Intent\UpdateLocationIntentForm;
use App\Intent\CreateLocationIntent;
use App\Intent\UpdateLocationIntent;
use App\IntentHandler\CreateLocationHandler;
use App\IntentHandler\UpdateLocationHandler;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/locations')]
class LocationController extends AbstractController
{
    #[IsGranted(User::ROLE_ADMIN)]
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

    #[IsGranted(User::ROLE_ADMIN)]
    #[Route('/{locationId}/update', name: 'update_location')]
    public function update(int $locationId, Request $request, UpdateLocationHandler $updateLocationHandler, LocationRepository $locationRepository): Response
    {
        $locationToUpdate = $locationRepository->find($locationId);
        if (null === $locationToUpdate) {
            throw $this->createNotFoundException("Location with id $locationId not found.");
        }
        $updateLocationIntent = new UpdateLocationIntent($locationToUpdate);
        $form = $this->createForm(UpdateLocationIntentForm::class, $updateLocationIntent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $updateLocationHandler->handle($updateLocationIntent);

            return $this->redirectToRoute(
                'view_weather_forecasts_by_city',
                ['cityName' => $location->getName(), 'countryCode' => $location->getCountry()]
            );
        }

        return $this->render('location/update.html.twig', ['form' => $form]);
    }

    #[Route('/', name: 'view_all_locations')]
    public function index(
        LocationRepository $locationRepository,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        if ($page <= 0) {
            throw $this->createNotFoundException('Invalid page');
        }
        $pageSize = $this->getParameter('page_size');
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
