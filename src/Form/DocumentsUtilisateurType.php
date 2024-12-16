<?php

namespace App\Form;

use App\Entity\DocumentsUtilisateur;
use App\Entity\TypeDocument;
use App\Entity\Dossier;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentsUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_document', EntityType::class, [
                'class' => TypeDocument::class,
                'choice_label' => 'name', // Adaptez à la propriété du typeDocument à afficher
                'label' => 'Type de document',
                'required' => true,
                'placeholder' => 'Sélectionnez un type de document',
            ])
            ->add('date_document', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'required' => false,
            ])
            ->add('dossier', EntityType::class, [
                'class' => Dossier::class,
                'choice_label' => 'name', 
                'label' => 'Dossier',
                'required' => false,
                'data' => $options['dossier'] ?? null,
                'placeholder' => 'Sélectionnez un dossier',
                'disabled' => true,
            ])
            ->add('details', TextareaType::class, [
                'label' => 'Détails',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajoutez des détails...',
                ],
            ])
            ->add('expediteur', TextType::class, [
                'label' => 'Expéditeur',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom de l\'expéditeur',
                ],
            ])
            ->add('destinataire', TextType::class, [
                'label' => 'Destinataire',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom du destinataire',
                ],
            ])
            ->add('dossier', EntityType::class, [
                'class' => Dossier::class,
                'choice_label' => 'name', // Adaptez à la propriété du Service à afficher
                'label' => 'Dossier',
                'required' => false,
                'placeholder' => 'Sélectionnez un dossier',
            ])
            ->add('file_path', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false, // Si le fichier n'est pas persisté automatiquement
                'required' => false,
            ])
            // ->add('client', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'nom', // Adaptez à la propriété de l'utilisateur à afficher
            //     'label' => 'Client',
            //     'required' => false,
            //     'placeholder' => 'Sélectionnez un client',
            // ])
            ->add('is_active', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentsUtilisateur::class,
            'dossier' => null,
        ]);
        $resolver->setDefined(['dossier']);
    }
}
