<?php

namespace App\Form;

use App\Entity\DocumentsUtilisateur;
use App\Entity\TypeDocument;
use App\Entity\Dossier;
use App\Entity\Services;
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
            ->add('documentsUtilisateurTypeDocument', EntityType::class, [
                'class' => TypeDocument::class,
                'choice_label' => 'name', // Adaptez à la propriété du typeDocument à afficher
                'label' => 'Type de document',
                'required' => true,
                'placeholder' => 'Sélectionnez un type de document',
            ])
            ->add('documentsUtilisateurDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'required' => false,
            ])
            ->add('documentsUtilisateurDossier', EntityType::class, [
                'class' => Dossier::class,
                'choice_label' => 'name', // Adaptez à la propriété du Dossier à afficher
                'label' => 'Dossier',
                'required' => false,
                'placeholder' => 'Sélectionnez un dossier',
            ])
            ->add('documentsUtilisateurDetails', TextareaType::class, [
                'label' => 'Détails',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajoutez des détails...',
                ],
            ])
            ->add('documentsUtilisateurExpediteur', TextType::class, [
                'label' => 'Expéditeur',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom de l\'expéditeur',
                ],
            ])
            ->add('documentsUtilisateurDestinataire', TextType::class, [
                'label' => 'Destinataire',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom du destinataire',
                ],
            ])
            ->add('documentsUtilisateurService', EntityType::class, [
                'class' => Services::class,
                'choice_label' => 'name', // Adaptez à la propriété du Service à afficher
                'label' => 'Service',
                'required' => false,
                'placeholder' => 'Sélectionnez un service',
            ])
            ->add('documentsUtilisateurFilePath', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false, // Si le fichier n'est pas persisté automatiquement
                'required' => false,
            ])
            ->add('documentsUtilisateurClient', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Adaptez à la propriété de l'utilisateur à afficher
                'label' => 'Client',
                'required' => false,
                'placeholder' => 'Sélectionnez un client',
            ])
            ->add('documentsUtilisateurIsActive', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocumentsUtilisateur::class,
        ]);
    }
}
