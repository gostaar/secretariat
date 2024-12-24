<?php
namespace App\Form;

use App\Entity\Events;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'événement',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'événement',
                'required' => true,
            ])
            ->add('location', TextType::class, [
                'label' => 'Lieu de l\'événement',
                'required' => false,  // Lieu peut être optionnel
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Date et heure de début',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Date et heure de fin',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('google_calendar_event_id', TextType::class, [
                'label' => 'ID de l\'événement Google Calendar',
                'required' => false,
            ])
            ->add('client', null, [
                'label' => 'Client',
                'required' => true,
                'class' => User::class,  // Remplacer User par le nom de votre entité User
                'choice_label' => 'name', // Utilisez un champ comme 'name' pour afficher les clients
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer l\'événement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}