<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('price')
      ->add('description')
      ->add('publishedAt')
      ->add('thumbnailCover')
      ->add('publisher', EntityType::class, [
        'class' => Publisher::class,
        'choice_label' => 'id',
      ])
      ->add('countries', EntityType::class, [
        'class' => Country::class,
        'choice_label' => 'id',
        'multiple' => true,
      ])
      ->add('categories', EntityType::class, [
        'class' => Category::class,
        'choice_label' => 'id',
        'multiple' => true,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Game::class,
    ]);
  }
}
