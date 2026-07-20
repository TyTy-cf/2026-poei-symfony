<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'category.name',
                'attr' => array(
                    'placeholder' => 'category.name_placeholder',
                    ''
                ),
            ])
            ->add('image', null, [
                'label' => 'category.image',
                'attr' => array(
                    'placeholder' => 'category.image_placeholder',
                    ''
                ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'translation_domain' => 'entity',
        ]);
    }
}
