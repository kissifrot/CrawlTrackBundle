<?php

namespace WebDL\CrawltrackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CrawlerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('officialURL', null, array(
                'label'  => 'Official URL'
            ))
            ->add('isHarmful', null, array(
                'label'  => 'Is the crawler harmful?',
                'required' => false
            ))
            ->add('userAgents', 'collection', array(
                'type' => new CrawlerUADataType(),
                'allow_add'    => true,
                'by_reference' => false
            ))
            ->add('ips', 'collection', array(
                'type' => new CrawlerIPDataType(),
                'allow_add'    => true,
                'by_reference' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WebDL\CrawltrackBundle\Entity\Crawler'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webdl_crawltrackbundle_crawler';
    }
}
