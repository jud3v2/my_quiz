<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
                $builder
                    ->add('email', EmailType::class, [
                        'attr' => ['autocomplete' => 'email'],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Entrer une adresse email',
                            ]),
                        ],
                    ])
                    ->add('username', TextType::class, [
                        'attr' => ['autocomplete' => 'username'],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Entrer un nom d\'utilisateur',
                            ]),
                        ],
                    ])
                    ->add('firstname', TextType::class, [
                        'attr' => ['autocomplete' => 'firstname'],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Entrer un prénom',
                            ]),
                        ],
                    ])
                    ->add('lastname', TextType::class, [
                        'attr' => ['autocomplete' => 'lastname'],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Entrer un nom',
                            ]),
                        ],
                    ])
                    ->add('agreeTerms', CheckboxType::class, [
                        'mapped' => false,
                        'constraints' => [
                            new IsTrue([
                                'message' => 'Vous devez accepter nos termes et conditions.',
                            ]),
                        ],
                    ])
                    ->add('plainPassword', PasswordType::class, [
                            // instead of being set onto the object directly,
                            // this is read and encoded in the controller
                        'mapped' => false,
                        'attr' => ['autocomplete' => 'new-password'],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Entrer un mot de passe',
                            ]),
                            new Length([
                                'min' => 6,
                                'minMessage' => 'Votre mot de passe doit contenir {{ limit }} charactères au minimum',
                                    // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]),
                        ],
                    ])
                    ->add('created_at', DateTimeType::class, [
                        'attr' => ['hidden' => true, 'value' => date('Y-m-d H:i:s')],
                    ])
                    ->add('updated_at', DateTimeType::class, [
                        'attr' => ['hidden' => true, 'value' => date('Y-m-d H:i:s')],
                    ])
                    ->add('uuid', TextType::class, [
                        'attr' => ['hidden' => true, 'value' => uniqid()],
                    ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
                $resolver->setDefaults([
                    'data_class' => User::class,
                ]);
        }
}
