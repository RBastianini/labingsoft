<?php

declare(strict_types=1);

namespace App\Form\DTO;

use App\DTO\TemperatureSpanDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemperatureSpanDTOForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'minimumCelsiusTemperature',
            NumberType::class
        )->add(
            'maximumCelsiusTemperature',
            NumberType::class
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', TemperatureSpanDTO::class);
    }
}
