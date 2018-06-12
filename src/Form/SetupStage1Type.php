<?php

namespace App\Form;

use App\Form\Type\FieldsetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetupStage1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('application_settings', FieldsetType::class, [
                'label'    => false,
                'legend'   => 'Setup your web application',
                'required' => false,
                'types'    => function (FormBuilderInterface $builder) {
                    $builder
                        ->add('siteName', TextType::class, [
                            'attr' => [
                                'placeholder' => 'e.g. My awesome site',
                            ],
                            'label'    => 'Site name',
                            'required' => true,
                        ])
                        ->add('siteUrl', UrlType::class, [
                            'data'     => '',
                            'label'    => 'URL',
                            'required' => true,
                        ]);
                },
            ])
            ->add('application_administrator', FieldsetType::class, [
                'legend' => 'Administrator',
                'types'  => function (FormBuilderInterface $builder) {
                    return $builder
                        ->add('username', TextType::class, [
                            'data'     => 'admin',
                            'label'    => 'Username',
                            'required' => true,
                        ])
                        ->add('password', PasswordType::class, [
                            'label'    => 'Password',
                            'required' => true,
                        ])
                        ->add('name', TextType::class, [
                            'attr' => [
                                'placeholder' => 'e.g. Jane Doe',
                            ],
                            'label'    => 'Name',
                            'required' => true,
                        ])
                        ->add('email', EmailType::class, [
                            'attr' => [
                                'placeholder' => 'e.g. somebody@example.com',
                            ],
                            'label'    => 'Email Address',
                            'required' => true,
                        ]);
                },
            ])
            ->add('application_administrator', FieldsetType::class, [
                'types' => function (FormBuilderInterface $builder) {
                    $builder
                        ->add('submit', SubmitType::class, [
                            'attr' => [
                                'class' => 'button button--positive',
                            ],
                            'label' => 'Continue…',
                        ]);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
