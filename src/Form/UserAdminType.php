<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null,[
                'label' => 'Pseudo : ',
                'attr' => [
                    'placeholder' => 'login'
                ],
            ])
            ->add('email', null,[
                'label' => 'Adresse mail : ',
                'attr' => [
                    'placeholder' => 'erwann.legall@eni.bzh'
                ],
            ])
            ->add('school', null,[
                'label' => 'Ecole : ',
                'attr' => [
                    'placeholder' => 'ENI Quimper'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}