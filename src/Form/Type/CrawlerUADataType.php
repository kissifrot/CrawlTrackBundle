<?php

namespace WebDL\CrawltrackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CrawlerUADataType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userAgent')
            ->add('isRegexp', null, array(
                'label'  => 'Is User Agent a Regexp ?',
                'required' => false
            ))
            ->add('isPartial', null, array(
                'label'  => 'Do we have a partial match? (LIKE %..% will be used)',
                'required' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WebDL\CrawltrackBundle\Entity\CrawlerUAData'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webdl_crawltrackbundle_crawler_ua_data';
    }
}
