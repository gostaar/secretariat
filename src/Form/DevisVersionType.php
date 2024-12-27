<?php

namespace App\Form;

use App\Entity\DevisVersion;
use App\Enum\DevisStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DevisVersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('commentaire')
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
            ])
            ->add('version', CheckboxType::class, [
                'label' => 'Version',
                'required' => true,
            ])
            ->add('date_modification', null, [
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer le devis',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DevisVersion::class,
        ]);
    }
}
