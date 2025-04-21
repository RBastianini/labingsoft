<?php

declare(strict_types=1);

namespace App\Form\DTO;

use App\DTO\ForecastDTO;
use App\Entity\Location;
use App\Enum\ShortWeatherDescription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForecastDTOForm extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'location',
            EntityType::class,
            [
                'label' => 'location',
                'class' => Location::class,
                'choice_label' => fn (Location $location) => "{$location->getName()} ({$location->getCountry()})",
            ]
        )->add(
            'day',
            DateType::class,
            [
                'label' => 'day',
                'input' => 'datetime_immutable',
                'widget' => 'choice',
            ]
        )->add(
            'shortWeatherDescription',
            ChoiceType::class,
            [
                'choices' => ShortWeatherDescription::cases(),
                'choice_label' => fn (ShortWeatherDescription $shortWeatherDescription) => $this->translator->trans('weather_description.'.$shortWeatherDescription->name),
                'label' => 'short_weather_description',
            ]
        )->add(
            'windSpeedKmh',
            NumberType::class,
            [
                'label' => 'wind_speed_kmh',
                'required' => false,
            ],
        )->add(
            'humidityPercentage',
            NumberType::class,
            [
                'label' => 'humidity_percentage',
                'required' => false,
            ],
        )->add(
            'temperatureSpan',
            TemperatureSpanDTOForm::class,
            [
                'label' => false,
            ],
        )->add(
            'submit',
            SubmitType::class,
            ['label' => 'save']
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ForecastDTO::class);
    }
}
