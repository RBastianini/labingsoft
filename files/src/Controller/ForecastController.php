<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\ForecastDTO;
use App\Entity\Forecast;
use App\Entity\User;
use App\Form\DTO\ForecastDTOForm;
use App\ValueObject\TemperatureSpan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forecasts')]
class ForecastController extends AbstractController
{
    #[IsGranted(User::ROLE_ADMIN)]
    #[Route('/create', name: 'create_forecast')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $forecastDTO = new ForecastDTO();
        $form = $this->createForm(ForecastDTOForm::class, $forecastDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forecast = new Forecast(
                $forecastDTO->location,
                $forecastDTO->day,
                $forecastDTO->shortWeatherDescription
            );
            $forecast->setWindSpeedKmh($forecastDTO->windSpeedKmh);
            if (null !== $forecastDTO->temperatureSpan) {
                $forecast->setTemperatureSpan(
                    new TemperatureSpan(
                        $forecastDTO->temperatureSpan->minimumCelsiusTemperature,
                        $forecastDTO->temperatureSpan->maximumCelsiusTemperature
                    )
                );
            }
            $forecast->setHumidityPercentage($forecastDTO->humidityPercentage);
            $entityManager->persist($forecast);
            $entityManager->flush();

            return $this->redirectToRoute('create_forecast');
        }

        return $this->render(
            'forecast/create.html.twig',
            ['form' => $form]
        );
    }
}
