<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierMotDePasseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('MotDePasseActuel', PasswordType::class, [
                'required' => true,
                'label' => 'Ancien mot de passe',
                'required' => true,
                'invalid_message' => "Votre ancien mot de passe n'est pas valide",
                'attr' => ['class' => 'password-field']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe doivent être identiques",
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => "Mot de passe"],
                'second_options' => ['label' => "Confirmation du mot de passe"]
            ])
            ->add('modifier', SubmitType::class, [
                'label' => "Modifier le Mot de passe",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnCreate btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
