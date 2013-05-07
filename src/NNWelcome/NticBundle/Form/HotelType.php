<?php

namespace NNWelcome\NticBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', 'checkbox', array('required' => false))
            ->add('sort')
            ->add('alias')
            ->add('longitude')
            ->add('latitude')
            ->add('object_type')
            ->add('price')
            ->add('translations', 'a2lix_translations', array(
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'operator' => array(
                        'label' => 'Operator'
                    ),
                    'short_description' => array(
                        'label' => 'Short description',
                        'type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    ),
                    'description' => array(
                        'label' => 'Description',
                        'type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    )
                )
            ))
            ->add('images', 'collection', array(
                'type' => new HotelImageType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
                ))
            ->add('files', 'collection', array(
                'type' => new HotelFileType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
                ))
            ->add('meta', new HotelMetaType())
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NNWelcome\NticBundle\Entity\Hotel'
        ));
    }

    public function getName()
    {
        return 'nnwelcome_nticbundle_hoteltype';
    }
}
