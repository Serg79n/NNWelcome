<?php

namespace NNWelcome\LocationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', 'checkbox', array('required' => false))
            ->add('translations', 'a2lix_translations', array(
                'fields' => array(
                    'title' => array(
                        'label' => 'Title',
                        'required' => true,
                    )
                )
            ))
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Area'),
            'data_class' => 'NNWelcome\LocationBundle\Entity\Area'
        ));
    }

    public function getName()
    {
        return 'nnwelcome_locationbundle_areatype';
    }
}
