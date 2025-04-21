<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LocationDTO;
use App\Entity\Location;
use App\Form\DTO\LocationDTOForm;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/locations')]
class LocationController extends AbstractController
{
    #[Route('/create', name: 'create_location')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $locationDTO = new LocationDTO();
        $form = $this->createForm(LocationDTOForm::class, $locationDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = new Location($locationDTO->name, $locationDTO->country);
            $location->setLatitude($locationDTO->latitude);
            $location->setLongitude($locationDTO->longitude);
            $entityManager->persist($location);
            $entityManager->flush();

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
