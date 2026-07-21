<?php

namespace App\Form;


use App\Entity\Review;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add(
        'content',
        TextareaType::class,
        [
          'label' => 'Content',
          'attr' => [
            'rows' => 5
          ]
        ]
      )
      // make degalt value 0
      ->add('rating', RangeType::class, [
        'label' => 'Rating',
        'attr' => [
          'min' => 0,
          'max' => 5,
          'step' => 1
        ],
        'data' => 0
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Review::class,
    ]);
  }
}
