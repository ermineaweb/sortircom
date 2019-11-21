<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie : '
            ])
            ->add('start', DateTimeType::class, [
                    'label' => 'Date et heure début de la sortie :',
                    //'widget' => 'single_text',
                    //'format' => 'yyyy-MM-dd  HH:mm',
                    //'attr' => [
                     //   'type' => 'dateTime-local'
                   // ]
                ])
            ->add('end', DateTimeType::class, [
                    'label' => 'Date et heure fin de la sortie :'
            ])
            ->add('limitdate', DateType::class, [
                    'label' => 'Date limite d\'inscription :',
                    'widget' => 'single_text'
            ])
            ->add('maxsize', null, [
                    'label' => 'Nombre de places :'
            ])
            ->add('description', null, [
                    'label' => 'Description et infos :'
            ])
            ->add('place', null, [
                    'label' => 'Lieu :'
            ])
            ->add('cancel', null, [
                    'label' => 'Motif de l\'annulation :'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
