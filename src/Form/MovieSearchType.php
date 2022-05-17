<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('genre_ids', EntityType::class, [
            'class' => Genre::class, // choisir la class Genre
            'choice_label' => 'name', // afficher le champ name des genres
            'multiple' => true,
            'expanded' => false,
            'attr' => array(
                'class' => 'form-control form-control-sm',
               
            ),
            'label' => 'Genre :',
            'required' => false,
            'row_attr' => array(
                'class' => 'col-12 col-md-6',
            ),
        ]);
        $builder->add('title', TextType::class, [
            'attr' => array(
                'class' => 'form-control form-control-sm',
                'placeholder' => 'Saisissez un titre de film'
            ),
            'label' => 'Titre :',
            'required' => false,
            'row_attr' => array(
                'class' => 'col-12 col-md-6',
            ),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }

    // cette methode retire le nom "car_search" sur le formulaire
    // car_search n'est donc plus dans $post, 
    // sans cette fonction : car_search est le parent des attributs energyOption, seat et km dans $post
    public function getBlockPrefix() {
        return '';
    }

}
