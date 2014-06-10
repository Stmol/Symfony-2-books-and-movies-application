<?php

namespace Axioma\MovieBundle\Form;

use Axioma\MovieBundle\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MovieType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('actors', 'collection', [
                'type'         => 'text',
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => [
                    'required' => false,
                ]
            ])
            ->add('quality', 'choice', [
                'empty_value' => false,
                'choices'     => [
                    Movie::QUALITY_DVDRIP => 'dvdrip',
                    Movie::QUALITY_HDRIP  => 'hdrip',
                    Movie::QUALITY_BDRIP  => 'bdrip',
                    Movie::QUALITY_dvd5   => 'dvd5',
                    Movie::QUALITY_720    => '720p',
                    Movie::QUALITY_1080   => '1080p',
                ],
            ])
            ->add('tags', 'tags', [
                'required' => false,
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Axioma\MovieBundle\Entity\Movie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'axioma_moviebundle_movie';
    }
}
