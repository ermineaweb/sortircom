<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'attr' => [
                    "placeholder" => "Nom de la ville...",
                ],
            ])
            ->add('zipcode', null, [
                'label' => 'Code postal',
                'help' => "Le code postal doit contenir 5 chiffres.",
                'attr' => [
                    "placeholder" => "Code postal...",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
