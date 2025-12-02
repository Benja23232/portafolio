<?php
// src/Form/ContactoType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;

class ContactoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'El nombre es obligatorio']),
                    new Length(['min' => 3, 'minMessage' => 'El nombre debe tener al menos 3 caracteres']),
                ],
                'attr' => [
                    'placeholder' => 'Tu nombre completo',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'El email es obligatorio']),
                    new Email(['message' => 'El email no es válido']),
                ],
                'attr' => [
                    'placeholder' => 'tu@email.com',
                    'class' => 'form-control'
                ]
            ])
            ->add('asunto', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'El asunto es obligatorio']),
                    new Length(['min' => 5, 'minMessage' => 'El asunto debe tener al menos 5 caracteres']),
                ],
                'attr' => [
                    'placeholder' => 'Asunto del mensaje',
                    'class' => 'form-control'
                ]
            ])
            ->add('mensaje', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'El mensaje es obligatorio']),
                    new Length(['min' => 10, 'minMessage' => 'El mensaje debe tener al menos 10 caracteres']),
                ],
                'attr' => [
                    'placeholder' => 'Escribe tu mensaje aquí...',
                    'rows' => 5,
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // No necesitamos entidad para este formulario
        ]);
    }
}