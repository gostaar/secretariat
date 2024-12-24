<?php

namespace App\Form;

use App\Entity\Facture;
use App\Enum\FactureStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
                'required' => true,
                'scale' => 2,
            ])
            ->add('date_facture', DateTimeType::class, [
                'label' => 'Date de la facture',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date_paiement', DateTimeType::class, [
                'label' => 'Date de paiement',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('status', EnumType::class, [
                'class' => FactureStatus::class,
                'choice_label' => fn (FactureStatus $choice) => $choice->getLabel(),
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('is_active', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer la facture',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
