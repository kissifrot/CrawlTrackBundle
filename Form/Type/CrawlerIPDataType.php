<?php

namespace WebDL\CrawltrackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CrawlerIPDataType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ipAddress')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WebDL\CrawltrackBundle\Entity\CrawlerIPData'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webdl_crawltrackbundle_crawler_ip_data';
    }
}
