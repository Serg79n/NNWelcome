<?php

namespace NNWelcome\LocationBundle\Form;

use \Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use NNWelcome\LocationBundle\Entity\Repository\CityRepository;

class CityFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('is_active', 'choice', array(
                'choices' => array(0 => 'No',1 => 'Yes'),
                'empty_value' => 'Yes or No',
                'required' => false))
            ;
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NNWelcome\LocationBundle\Entity\City'
        ));
    }

    public function getName()
    {
        return 'NNWelcome_locationbundle_cityfiltertype';
    }
}
