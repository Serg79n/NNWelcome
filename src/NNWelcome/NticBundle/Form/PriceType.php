<?php

namespace NNWelcome\NticBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from')
            ->add('to')
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NNWelcome\NticBundle\Entity\Price'
        ));
    }

    public function getName()
    {
        return 'nnwelcome_nticbundle_pricetype';
    }
}
