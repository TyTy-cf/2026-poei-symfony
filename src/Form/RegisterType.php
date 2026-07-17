<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\User;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'user.property.email',
                ]
            ])
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'user.property.name',
                ]
            ])
            ->add('nickname', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'user.property.nickname',
                ]
            ])
            ->add('country', EntityType::class, [
                'label' => false,
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => 'user.property.select_country',
                'required' => false,
                'query_builder' => function (CountryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('profileImage', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'user.property.profileImage',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'label' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'user.repeat_password',
                'required' => true,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'user.property.password',
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'user.property.repeatPassword',
                    ]
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'entity',
            'label' => false,
        ]);
    }
}
