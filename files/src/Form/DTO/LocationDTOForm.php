<?php

declare(strict_types=1);

namespace App\Form\DTO;

use App\DTO\LocationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationDTOForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            ['label' => 'name'],
        )->add(
            'country',
            CountryType::class,
            [
                'label' => 'country',
                'placeholder' => 'choose_one',
            ],
        )->add(
            'latitude',
            NumberType::class,
            [
                'label' => 'latitude',
                'required' => false,
            ],
        )->add(
            'longitude',
            NumberType::class,
            [
                'label' => 'longitude',
                'required' => false,
            ],
        )->add( // This should not go here
            'submit',
            SubmitType::class,
            ['label' => 'save'],
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', LocationDTO::class);
    }
}
