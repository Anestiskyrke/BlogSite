<?php

namespace App\Form;

use App\Entity\BlogPost;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EntryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'slug',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'tinymce', 'class' => 'form-control'],
                    'required' => false
                ]
            )
            ->add(
                'body',
                TextareaType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'tinymce', 'class' => 'form-control', 'cols'=>10,'rows'=>10],
                    'required' => false
                ]
            )
            ->add(
                'imageURL',
                TextareaType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'tinymce', 'class' => 'form-control'],
                    'required' => false
                ]
            )
            ->add(
                'category',
                ChoiceType::class,
                [
                    'multiple' => false,
                    // these options are passed to each "checkbox" type
                    'choices' => [
                        'Categories' => [
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
                'relatedPosts',
                EntityType::class,
                [
                    'class'     => BlogPost::class,
                    'choice_label' => 'title',
                    'expanded'  => true,
                    'multiple'  => true,
                ]
            )
            ->add(
                'create',
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
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'author_form';
    }
}
