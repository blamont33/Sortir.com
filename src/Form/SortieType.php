<?php

namespace App\Form;

use App\Entity\Sortie;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieType extends AbstractType
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
            ->add('campus', TextType::class, [
                'mapped'=>false,
                'attr'=>['readonly'=>true]
            ])
            ->add('codePostal', TextType::class, [
                'mapped'=>false,
                'attr'=>['readonly'=>true]
            ])

            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'publier']);

        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                $form->getParent()->add('lieu', EntityType::class, [
                    'class' => 'App\Entity\Lieu',
                    'placeholder' => 'Choisir un lieu',
                    'choices' => $form->getData()->getLieux()
                ]);
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                $lieu = $data->getLieu();

                if ($lieu) {
                    $form->get('ville')->setData($lieu->getVille());

                    $form->add('lieu', EntityType::class, [
                        'class' => 'App\Entity\Lieu',
                        'placeholder' => 'Choisir un lieu',
                        'choices' => $lieu->getVille()->getLieux()
                    ]);

                } else {
                    $form->add('lieu', EntityType::class, [
                        'class' => 'App\Entity\Lieu',
                        'placeholder' => 'Choisir un lieu',
                        'choices' => []
                    ]);

                }

            }
        );
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
