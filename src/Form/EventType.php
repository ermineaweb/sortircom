<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la sortie',
                'attr' => [
                    'placeholder' => 'Nom de votre sortie...'
                ],
            ])
            ->add('start',null, [
                    'label' => 'Date et heure du début de la sortie',
                ])
            ->add('end', DateTimeType::class, [
                    'label' => 'Date et heure de la fin de la sortie'
            ])
            ->add('limitdate', DateType::class, [
                    'label' => 'Date limite d\'inscription :',
                    'widget' => 'single_text'
            ])
            ->add('maxsize', null, [
                    'label' => 'Nombre de places'
            ])
            ->add('description', null, [
                    'label' => 'Description et infos',
                    'attr' => [
                        'placeholder' => 'Description de votre sortie...'
                    ],
            ])
            ->add('place', null, [
                    'label' => 'Lieu',
            ])
          ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
