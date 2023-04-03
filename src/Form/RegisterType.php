<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'invalid_message' => 'The username is already taken.',
                'attr' => ['class' => 'form-control'],
                'label' => 'Username',
                'label_attr' => ['class' => 'form-label'],
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'invalid_message' => 'This email already exists.',
                'attr' => ['class' => 'form-control'],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'],
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'invalid_message' => 'Must be at least 2 characters long.',
                'attr' => ['class' => 'form-control'],
                'label' => 'First Name',
                'label_attr' => ['class' => 'form-label'],
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'invalid_message' => 'Must be at least 2 characters long.',
                'attr' => ['class' => 'form-control'],
                'label' => 'Last Name',
                'label_attr' => ['class' => 'form-label'],
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options' => [
                    'label' =>
                        'Password', 'label_attr' => ['class' => 'form-label']
                ],
                'second_options' => [
                    'label' =>
                        'Confirm Password', 'label_attr' => ['class' => 'form-label']
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['class' => 'btn btn-info btn-lg btn-block']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
