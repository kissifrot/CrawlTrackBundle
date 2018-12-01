<?php

namespace WebDL\CrawltrackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WebDL\CrawltrackBundle\Entity\Crawler;

class CrawlerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('officialURL', null, [
                'label' => 'Official URL'
            ])
            ->add('isHarmful', CheckboxType::class, [
                'label'    => 'Is the crawler harmful?',
                'required' => false
            ])
            ->add('userAgents', CollectionType::class, [
                'type'         => new CrawlerUADataType(),
                'allow_add'    => true,
                'by_reference' => false
            ])
            ->add('ips', CollectionType::class, [
                'type'         => new CrawlerIPDataType(),
                'allow_add'    => true,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Crawler::class
        ]);
    }

    public function getName(): string
    {
        return 'webdl_crawltrackbundle_crawler';
    }
}
