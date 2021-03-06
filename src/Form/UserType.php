<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
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
			->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label'=> 'Mot de passe : '),
                'second_options' => array('label'=>'Confirmer le mot de passe : '),
            ))
			->add('lastname', null,[
                'label' => 'Nom : ',
                'attr' => [
                    'placeholder' => 'Legall'
                ],
            ])
			->add('firstname', null,[
                'label' => 'Prénom : ',
                'attr' => [
                    'placeholder' => 'Erwann'
                ],
            ])
			->add('phone', null,[
                'label' => 'Numéro de téléphone : ',
                'attr' => [
                    'placeholder' => '06-XX-XX-XX-XX'
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
			/*
			->add('avatar', FileType::class, [
				'label' => 'Brochure (PDF file)',
				'mapped' => false,
				'required' => false,
				// unmapped fields can't define their validation using annotations
				// in the associated entity, so you can use the PHP constraint classes
				'constraints' => [
					new File([
						'maxSize' => '1024k',
						'mimeTypes' => [
							'image/jpg',
							'image/png',
						],
						'mimeTypesMessage' => 'JPG ou PNG uniquement',
					])
				],
			])
			*/
		;
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
	
	
}
