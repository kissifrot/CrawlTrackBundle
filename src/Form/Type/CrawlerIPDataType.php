<?php

namespace WebDL\CrawltrackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WebDL\CrawltrackBundle\Entity\CrawlerIPData;

class CrawlerIPDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ipAddress')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => CrawlerIPData::class
        ));
    }

    public function getName(): string
    {
        return 'webdl_crawltrackbundle_crawler_ip_data';
    }
}
