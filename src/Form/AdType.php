<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                $this->getConfiguration("Titre", "Tapez un titre pour votre annonce")
            )
            ->add('slug', TextType::class,
                $this->getConfiguration("slug", "Tapez un slug pour votre annonce (auto si vide)", [
                    'required' => false
                ])
            )
            ->add('price', MoneyType::class,                
                $this->getConfiguration("Prix", "Tapez un prix pour votre annonce")
            )
            ->add('introduction', TextType::class,                
                $this->getConfiguration("Introduction", "Tapez une introduction pour votre annonce")
            )
            ->add('contentt', TextareaType::class,                
                $this->getConfiguration("Description", "Tapez une description pour votre annonce")
            )
            ->add('coverImage', UrlType::class,                
                $this->getConfiguration("Image", "Entrez l'url d'une image pour votre annonce")
            )
            ->add('rooms', IntegerType::class,                
                $this->getConfiguration("Chambres", "Entrer le nombre de champs pour votre annonce")
            )
            ->add('images', CollectionType::class,
                [
                    'entry_type'=> ImageType::class,
                    'allow_add'=> true,
                    'allow_delete' => true
                ]
            
            )
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
