<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ThirdPartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new  Assert\NotBlank([
                        'message' => 'Title should not be blank.',
                    ]),
                    new  Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Title cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('body', TextType::class, [
                'constraints' => [
                    new  Assert\NotBlank([
                        'message' => 'Body should not be blank.',
                    ]),
                    new  Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Body cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('userId', null, [
                'constraints' => [
                    new  Assert\NotBlank([
                        'message' => 'User ID should not be blank.',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
}
