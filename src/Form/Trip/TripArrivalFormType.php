<?php

namespace App\Form\Trip;

use App\Validator\ValidAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TripArrivalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('arrivalAddress', TextType::class, [
            'label' => 'Où allez-vous ?',
            'attr' => [
                'data-address-formatter-target' => 'input',
                'autocomplete' => 'off',
                'placeholder' => 'Saisissez l\'adresse précise',
            ],
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir une adresse de départ']),
                new ValidAddress(),
            ],
        ]);
    }
}