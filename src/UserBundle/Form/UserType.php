<?php
/**
 * Created by PhpStorm.
 * User: talend
 * Date: 10/07/2017
 * Time: 15:09
 */

namespace UserBundle\Form;

use FOS\UserBundle\Form\Type\UsernameFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add(
                'roles', ChoiceType::class, array(
                    'choices' => array(
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_CUSTOMER' => 'ROLE_CUSTOMER'
                    ),
                    'expanded' => true,
                    'multiple' => true,
                )
            )
            ->add('save', SubmitType::class);
    }
}