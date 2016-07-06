<?php

namespace Flowcode\SocialHubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocialNetworkType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type')
            ->add('description')
            ->add('clientId')
            ->add('clientSecret')
            ->add('loginEnabled')
            ->add('enabled');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Flowcode\SocialHubBundle\Entity\SocialNetwork',
            'translation_domain' => 'SocialNetwork',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'socialnetwork';
    }
}
