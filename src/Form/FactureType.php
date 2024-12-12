<?php

namespace App\Form;

use App\Entity\Facture;
use App\Enum\FactureStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('factureMontant', NumberType::class, [
                'label' => 'Montant',
                'required' => true,
                'scale' => 2,
            ])
            ->add('factureDateFacture', DateTimeType::class, [
                'label' => 'Date de la facture',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('factureDatePaiement', DateTimeType::class, [
                'label' => 'Date de paiement',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('factureStatus', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => array_combine(
                    array_map(fn($case) => $case->name, FactureStatus::cases()), // Utilisation de `name` comme clé
                    array_map(fn($case) => (string) $case->value, FactureStatus::cases()) // Convertir la valeur de l'énumération en string
                ),
                'required' => true,
            ])
            ->add('factureCommentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('factureIsActive', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
