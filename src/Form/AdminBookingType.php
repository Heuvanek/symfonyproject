<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class,
            $this->getConfiguration("Date de début", "indiquez la date de début",[
                'widget' => 'single_text',
            ]))
            ->add('endDate', DateType::class,
            $this->getConfiguration("Date de fin", "indiquez la date de fin",[
                'widget' => 'single_text',
            ]))
            ->add('comment', TextareaType::class,
            $this->getConfiguration("commentaire", "Laissez ici vos commentaires et précisions sur la réservation",[
                "required" => false
            ]))
            ->add('booker', EntityType::class,
            $this->getConfiguration("Reserveur", "Indiquez qui a réservé l'annonce",[
                'class'=> User::class,
                'choice_label' => function($user){
                    return $user->getFirstName()." ".strToUpper($user->getLastName());
                }
            ]))
            ->add('ad', EntityType::class,
            $this->getConfiguration("Annonce", "indiquez de quelle annocne il s'agit",[
                'class'=> Ad::class,
                'choice_label' => 'title',
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
