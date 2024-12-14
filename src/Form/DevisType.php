<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\User;
use App\Enum\DevisStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Montant du devis',
                'required' => true,
            ])
            ->add('date_devis', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date du devis',
                'required' => true,
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajouter un commentaire (facultatif)',
                ],
            ])
            ->add('is_active', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => array_combine(
                    array_map(fn($case) => $case->name, DevisStatus::cases()), // Utilisation de `name` comme clé
                    array_map(fn($case) => (string) $case->value, DevisStatus::cases()) // Convertir la valeur de l'énumération en string
                ),
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
