<?php
// src/Form/RepertoireType.php

namespace App\Form;

use App\Entity\Repertoire;
use App\Entity\Contact;
use App\Entity\User;
use App\Entity\Dossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepertoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal',
                'required' => true,
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => true,
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'required' => true,
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'required' => true,
            ])
            ->add('mobile', TelType::class, [
                'label' => 'Mobile',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('siret', TextType::class, [
                'label' => 'SIRET',
                'required' => true,
            ])
            ->add('nom_entreprise', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'required' => false,
            ])
            ->add('client', null, [
                'label' => 'Client',
                'required' => true,
                'class' => User::class,
                'choice_label' => 'nom',  // Afficher le nom d'utilisateur du client
            ])
            ->add('dossier', null, [
                'label' => 'Dossier',
                'required' => false,
                'class' => Dossier::class,
                'choice_label' => 'id', // Afficher l'ID ou un autre champ du dossier
            ])
            ->add('contact', CollectionType::class, [
                'label' => 'Contacts',
                'entry_type' => ContactType::class,  // Utilisez un formulaire ContactType pour gérer l'ajout de contacts
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,  // Important pour que les contacts soient correctement gérés
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer le répertoire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repertoire::class,
        ]);
    }
}