<?php

namespace GameScoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Request;

class ScoreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->players_out = $options['players_out'];
        $this->var = $options['play_id'];

        $builder
            ->add('score', IntegerType::class)
            ->add('player', EntityType::class,
                array(
                    'class' => 'GameScoreBundle:Player',
                    'choice_label' => 'firstname',
                    'multiple' => false)
            )
            ->add('play', EntityType::class,
                array(
                    'class' => 'GameScoreBundle:Play',
                    'choice_label' => 'id',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->andWhere('p.id = :play_id')
                            ->setParameter('play_id', $this->var);
                    },
                    'multiple' => false)
            )
            ->add('player', EntityType::class,
                array(
                    'class' => 'GameScoreBundle:Player',
                    'choice_label' => function ($player) {
                        return $player->getFirstname() . ' ' . $player->getLastname();
                    },
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->andWhere('p.id NOT IN (' . implode("," , $this->players_out) . ')')
                        ;
                    },
                    'multiple' => false)
            )
            ->add('save_and_declare_other_players', SubmitType::class)
            ->add('save_and_stop', SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GameScoreBundle\Entity\Score',
            'play_id' => '1',
            'players_out' => array()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gamescorebundle_score';
    }


}
