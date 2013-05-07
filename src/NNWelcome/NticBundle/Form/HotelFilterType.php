<?php

namespace NNWelcome\NticBundle\Form;

use \Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use NNWelcome\NticBundle\Entity\Repository\HotelRepository;

class HotelFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('object_type', 'entity', array(
                'class' => 'NticBundle:ObjectType',
                'property' => 'title',
                'required' => true,
                'empty_value' => " "
            ))
            ->add('price', 'entity', array(
                'class' => 'NticBundle:Price',
                'property' => 'range',
                'required' => true,
                'empty_value' => " "
            ))
            ->add('is_active', 'choice', array(
                'choices' => array(0 => 'No',1 => 'Yes'),
                'empty_value' => 'Yes or No',
                'required' => false))
            ;
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Hotel'),
            'data_class' => 'NNWelcome\NticBundle\Entity\Hotel'
        ));
    }

    public function getName()
    {
        return 'NNWelcome_nticbundle_hotelfiltertype';
    }
}
