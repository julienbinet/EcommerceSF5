<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints'=> new Length(null,2,30) ,
                'attr'  => [
                    'placeholder' => 'Prénom'
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'constraints'=> new Length(null,2,30) ,
                'attr'  => [
                    'placeholder' => 'Nom'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr'  => [
                    'placeholder' => 'Email'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.',
                'required'        => true,
                'first_options'   => [
                    'label' => 'Votre mot de passe',
                    'attr'  => [
                        'placeholder' => 'Saisissez votre mot de passe svp'
                    ]
                ],
                'second_options'   => [
                    'label' => 'Confirmation de votre mot de passe',
                    'attr'  => [
                        'placeholder' => 'Confirmez votre mot de passe svp'
                    ]
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
