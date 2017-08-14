<?php

namespace GameScoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use GameScoreBundle\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('has_inverted_score', CheckboxType::class,
                array('required' => false))
            ->add('is_collaborative', CheckboxType::class,
                array('required' => false))
            ->add('is_extension', CheckboxType::class,
                array('required' => false))
            ->add('year', TextType::class)
            ->add('editor', EntityType::class,
                array(
                    'class' => 'GameScoreBundle:Editor',
                    'choice_label' => 'name',
                    'multiple' => false
                )
            )
            ->add('author', EntityType::class, array(
                'choice_label' => function (Author $author) {
                    return $author->getFirstname() . ' ' . $author->getLastname();
                },
                'class' => 'GameScoreBundle:Author',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.lastname', 'ASC');
                },
                'multiple' => true
            ))
            ->add('main_card_url', TextType::class,
                array('required' => false)
            )
            ->add('save', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GameScoreBundle\Entity\Game'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gamescorebundle_game';
    }


}
