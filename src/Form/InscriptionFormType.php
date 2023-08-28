<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class InscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('courriel', EmailType::class, [
                'required' => true,
                'label' => 'Courriel',
                'attr' => []
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe doivent être identiques",
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => "Mot de passe"],
                'second_options' => ['label' => "Confirmation du mot de passe"]
            ])
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'constraints' => [new Assert\Length(min: 2, minMessage: "Le nom doit contenir au moins {{ limit }} caractères")],
                'constraints' => [new Assert\Length(max: 30, maxMessage: "Le nom doit contenir au maximum {{ limit }} caractères")],
                'attr' => []
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
                'attr' => []
            ])
            ->add('adresse', TextType::class, [
                'required' => true
            ])
            ->add('ville', TextType::class, [
                'required' => true,
            ])
            ->add('codePostal', TextType::class, [
                'required' => true,
            ])
            ->add('province', ChoiceType::class, [
                'choices' => [
                    'Québec' => 'QC',
                    'Ontario' => 'ON',
                    'Nouvelle-Écosse' => 'NS',
                    'Nouveau-Brunswick' => 'NB',
                    'Manitoba' => 'MB',
                    'Colombie-Britannique' => 'BC',
                    'Île-du-Prince-Édouard' => 'PE',
                    'Saskatchewan' => 'SK',
                    'Alberta' => 'AB',
                    'Terre-Neuve-et-Labrador' => 'NL',
                    'Territoires du Nord-Ouest' => 'NT',
                    'Yukon' => 'YT',
                    'Nunavut' => 'NU',
                ]
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
            ])
            ->add('creer', SubmitType::class, [
                'label' => "Créer votre compte",
                'row_attr' => ['class' => 'form-button'],
                'attr' => ['class' => 'btnCreate btn-primary']
            ]);

        $builder->get('telephone')->addModelTransformer(new CallbackTransformer(
            function ($telephoneFromDatabase) {
                $nouveauTelephone = substr_replace($telephoneFromDatabase, "-", 3, 0);
                return substr_replace($nouveauTelephone, "-", 7, 0);
            },
            function ($telephoneFromView) {
                return str_replace("-", "", $telephoneFromView);
            }
        ));

        $builder->get('codePostal')->addModelTransformer(new CallbackTransformer(
            function ($codePostalFromDatabase) {
                return substr_replace($codePostalFromDatabase, " ", 3, 0);
            },
            function ($codePostalFromView) {
                return str_replace(" ", "", $codePostalFromView);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
