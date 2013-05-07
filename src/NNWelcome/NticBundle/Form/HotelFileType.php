<?php

namespace NNWelcome\NticBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HotelFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file')
            ->add('file_category')
            ->add('sort')
            ->add('translations', 'a2lix_translations', array(
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'description' => array(
                        'label' => 'Description',
                        'type' => 'textarea',
                    )
                ),
                'label' => ' '
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NNWelcome\NticBundle\Entity\HotelFile'
        ));
    }

    public function getName()
    {
        return 'nnwelcome_nticbundle_hotelfiletype';
    }
}
