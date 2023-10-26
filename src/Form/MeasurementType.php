<?php

namespace App\Form;

use App\Entity\Measurement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Validator\Constraints as Assert; 
use App\Entity\Location; 

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', null, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
            ],
        ])
        ->add('temperature', null, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'float']),
                new Assert\Regex([
                    'pattern' => '/^\d+(\.\d{2})?$/',
                    'message' => 'Temperature should be in the format XX.XX or X.XX.',
                ]),
            ],
        ])
        ->add('humidity', null, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'float']),
                new Assert\Range([
                    'min' => 0,
                    'max' => 100,
                    'notInRangeMessage' => 'Humidity should be between {{ min }} and {{ max }}.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^\d+(\.\d{2})?$/',
                    'message' => 'Humidity should be in the format XX.XX or X.XX.',
                ]),
            ],
        ])
            ->add('location', EntityType::class, [
                'class' => Location::class, 
                'choice_label' => 'city',
                'constraints' => [
                    new Assert\NotBlank(),
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
