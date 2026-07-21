<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Publisher;
use App\Repository\CountryRepository;
use App\Repository\GameRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdAt', null, [
                'label' => 'publisher.property.created_at',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'publisher.property.created_at',
                ]
            ])
            ->add('name', null, [
                'label' => 'publisher.property.name',
                'attr' => [
                    'placeholder' => 'publisher.property.name',
                ]
            ])
            ->add('website', null, [
                'label' => 'publisher.property.website',
                'attr' => [
                    'placeholder' => 'publisher.property.website',
                ]
            ])
            ->add('country', EntityType::class, [
                'label' => 'publisher.property.country',
                'class' => Country::class,
                'choice_label' => 'name',
                'query_builder' => function (CountryRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ])
            ->add('games', EntityType::class, [
                'label' => 'publisher.property.games',
                'class' => Game::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (GameRepository $gr) {
                    return $gr->createQueryBuilder('g')
                        ->where('g.publisher IS NULL')
                        ->orderBy('g.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publisher::class,
            'translation_domain' => 'entity',
        ]);
    }
}
