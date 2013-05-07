<?php

namespace NNWelcome\NticBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HotelMetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations', array(
                'fields' => array(
                    'meta_title' => array(
                        'label' => 'Meta title'
                    ),
                    'meta_keywords' => array(
                        'label' => 'Meta keywords'
                    ),
                    'meta_description' => array(
                        'label' => 'Meta description'
                    ),
                ),
                'label' => ' '
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NNWelcome\NticBundle\Entity\HotelMeta'
        ));
    }

    public function getName()
    {
        return 'nnwelcome_nticbundle_hotelmetatype';
    }
}
