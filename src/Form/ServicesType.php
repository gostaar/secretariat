<?php

namespace App\Form;

use App\Entity\Services;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('servicesName', TextType::class, [
                'label' => 'Service Name',
                'required' => true,
                'attr' => ['placeholder' => 'Enter the name of the service']
            ])
            ->add('servicesDocumentsUtilisateurs', CollectionType::class, [
                'entry_type' => DocumentsUtilisateurType::class, // Vous devez définir ce formulaire pour DocumentsUtilisateur
                'allow_add' => true,
                'by_reference' => false,
                'required' => false,
                'label' => 'Documents Utilisateurs',
                'attr' => ['class' => 'documents-utilisateurs-collection'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Services::class,
        ]);
    }
}
