<?php

namespace Flowcode\SocialHubBundle\Form\Type;

use Flowcode\SocialHubBundle\Model\FacebookSocialProvider;
use Flowcode\SocialHubBundle\Model\TwitterSocialProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    FacebookSocialProvider::PROVIDER_TYPE => 'Facebook',
                    TwitterSocialProvider::PROVIDER_TYPE => 'Twitter',
                ),
            ))
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
