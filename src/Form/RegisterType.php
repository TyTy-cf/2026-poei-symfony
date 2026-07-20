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
                'label' => 'user.email',
                'attr' => array(
                    'placeholder' => 'user.email_placeholder',
                    ''
                ),
            ])
            ->add('name', null, [
                'label' => 'user.name',
                'attr' => array(
                    'placeholder' => 'user.name_placeholder',
                ),
            ])
            ->add('nickname', null, [
                'label' => 'user.nickname',
                'attr' => array(
                    'placeholder' => 'user.nickname_placeholder',
                ),
            ])
            ->add('password', PasswordType::class, [
                'label' => 'user.password',
                'attr' => array(
                    'placeholder' => 'user.password_placeholder',
                    'maxlength' => 10,
                ),
            ])
            ->add('passwordRepeat', RepeatedType::class, [
                'label' => 'user.password',
                'type' => PasswordType::class,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'user.password_placeholder',
                    'maxlength' => 10,
                ),
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'nationality',
                'label' => 'user.country',
                'query_builder' => function (CountryRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.nationality', 'ASC');
                },
            ])
            ->add('profileImage', null, [
                'label' => 'user.profile_picture',
                'attr' => array(
                    'placeholder' => 'whatever'
                ),
            ]);
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
