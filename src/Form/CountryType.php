<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', null, [
                'label' => 'country.property.code',
                'attr' => [
                    'placeholder' => 'country.property.code',
                ]
            ])
            ->add('name', null, [
                'label' => 'country.property.name',
                'attr' => [
                    'placeholder' => 'country.property.name',
                ]
            ])
            ->add('nationality', null, [
                'label' => 'country.property.nationality',
                'attr' => [
                    'placeholder' => 'country.property.nationality',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
            'translation_domain' => 'entity',
        ]);
    }
}
