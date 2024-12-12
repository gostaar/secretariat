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

class DevisVersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('devisVersionMontant', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Montant de la version du devis',
                'required' => true,
            ])
            ->add('devisVersionDateDevis', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de la version du devis',
                'required' => true,
            ])
            ->add('devisVersionCommentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ajoutez un commentaire...',
                ],
            ])
            ->add('devisVersionIsActive', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ])
            ->add('devisVersionStatus', ChoiceType::class, [
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
            'data_class' => DevisVersion::class,
        ]);
    }
}
