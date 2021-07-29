<?php

namespace App\Form;

use App\Entity\Sortie;

use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateDebut')
            ->add('duree')
            ->add('dateCloture', DateType::class)
            ->add('nbInscriptionMax')
            ->add('descriptionInfo')
            ->add('ville', EntityType::class, [
                'class' => 'App\Entity\Ville',
                'placeholder' => 'Choisir une ville',
                'mapped' => false,
            ])
            ->add('lieu', EntityType::class, [
                'class' => 'App\Entity\Lieu',
                'placeholder' => 'Choisir un lieu',
                'choices'=>[],
                'mapped' => false,
            ])

            ->add('rue', TextType::class,[
                'mapped'=>false,
                'attr'=>['readonly'=>true]

            ])
            ->add('latitude', TextType::class,[
                'mapped'=>false,
                'attr'=>['readonly'=>true]
            ])
            ->add('longitude', TextType::class,[
                'mapped'=>false,
                'attr'=>['readonly'=>true]
            ])
            ->add('campus')
            ->add('codePostal', TextType::class, [
                'mapped'=>false,
                'attr'=>['readonly'=>true]
            ])

            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'publier']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
