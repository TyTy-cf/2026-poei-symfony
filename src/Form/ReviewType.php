<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'review.property.content',
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'review.property.content',
                ]
            ])
            ->add('rating', null, [
                'label' => 'review.property.rating',
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1,
                    'placeholder' => 'review.property.rating',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
            'translation_domain' => 'entity',
        ]);
    }
}
