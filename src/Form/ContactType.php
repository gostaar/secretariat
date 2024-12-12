<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Repertoire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contactNom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => ['placeholder' => 'Entrez le nom du contact'],
            ])
            ->add('contactTelephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez le numéro de téléphone'],
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => ['placeholder' => 'Entrez l\'adresse email'],
            ])
            ->add('contactRole', ChoiceType::class, [
                'label' => 'Rôle',
                'required' => false,
                'choices' => [
                    'Client' => 'Client',
                    'Fournisseur' => 'Fournisseur',
                    'Partenaire' => 'Partenaire',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Sélectionnez un rôle',
            ])
            ->add('contactCommentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => ['placeholder' => 'Ajoutez un commentaire'],
            ])
            ->add('contactRepertoire', EntityType::class, [
                'class' => Repertoire::class,
                'choice_label' => 'nom',
                'label' => 'Répertoire',
                'required' => false,
                'placeholder' => 'Sélectionnez un répertoire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
