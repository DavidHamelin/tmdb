<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('originalTitle')
            ->add('originalLanguage')
            ->add('genre_ids', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                // 'expanded' => true,
                'required' => false
            ])
            ->add('posterPath')
            ->add('releaseDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('overview')
            ->add('popularity')
            ->add('video')
            ->add('voteAverage')
            ->add('voteCount')
            ->add('adult')
            ->add('backdropPath');
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
