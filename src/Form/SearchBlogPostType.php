<?php

namespace App\Form;

use App\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add(
            'category',
            ChoiceType::class,
            [
                'multiple' => false,
                // these options are passed to each "checkbox" type
                'choices' => [
                    'Categories' => [
                        null => '',
                        'anime' => 'anime',
                        'work' => 'work',
                        'gaming' => 'gaming',
                        'health' => 'health',
                        'OS' => 'OS'
                    ]
                ],
                'choice_attr' => [
                    'anime' => ['category' => 'anime'],
                    'work' => ['category' => 'work'],
                    'health' => ['category' => 'health'],
                    'gaming' => ['category' => 'gaming'],
                    'OS' => ['category' => 'OS']
                ],
            ]
        )
        ->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['class' => 'form-control btn-primary pull-right'],
                'label' => 'Create!'
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
