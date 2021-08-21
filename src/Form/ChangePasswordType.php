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

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                "label"    => "Mon mail"
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                "label"    => "Mon prénom"
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                "label"    => "Mon nom"
            ])
            ->add('old_password', PasswordType::class, [
                "mapped" => false,
                'label'  => "Mon mot de passe actuel",
                "attr"   => [
                    "placeholder" => "Veuillez saisir votre mot de passe actuel"
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                "mapped"          => false,
                'type'            => PasswordType::class,
                'invalid_message' => 'Le nouveau mot de passe et la confirmation doivent être identique.',
                'required'        => true,
                'first_options'   => [
                    'label' => 'Mon nouveau mot de passe',
                    'attr'  => [
                        'placeholder' => 'Saisissez votre nouveau mot de passe svp'
                    ]
                ],
                'second_options'  => [
                    'label' => 'Confirmer votre nouveau mot de passe',
                    'attr'  => [
                        'placeholder' => 'Confirmez votre nouveau mot de passe svp'
                    ]
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
