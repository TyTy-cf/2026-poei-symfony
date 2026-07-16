<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', null, [
        'label' => 'user.properties.email',
        'attr' => [
          'placeholder' => 'user.properties.email',
        ],
      ])
      ->add('name', null, [
        'label' => 'user.properties.name',
        'attr' => [
          'placeholder' => 'user.properties.name',
        ],
      ])
      ->add('nickname', null, [
        'label' => 'user.properties.nickname',
        'attr' => [
          'placeholder' => 'user.properties.nickname',
        ],
      ])
      ->add('password', PasswordType::class, [
        'label' => 'user.properties.password',
        'attr' => [
          'placeholder' => 'user.properties.password',
        ],
      ])
      ->add('profileImage', null, [
        'label' => 'user.properties.profileImage',
        'attr' => [
          'placeholder' => 'user.properties.profileImage',
        ],
      ])
      ->add('country', EntityType::class, [
        'class' => Country::class,
        'choice_label' => 'name',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
      'translation_domain' => 'messages',
    ]);
  }
}
