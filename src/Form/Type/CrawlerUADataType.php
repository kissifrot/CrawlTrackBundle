<?php

namespace WebDL\CrawltrackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WebDL\CrawltrackBundle\Entity\CrawlerUAData;

class CrawlerUADataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userAgent')
            ->add('isRegexp', CheckboxType::class, [
                'label'    => 'Is User Agent a Regexp ?',
                'required' => false
            ])
            ->add('isPartial', CheckboxType::class, [
                'label'    => 'Do we have a partial match? (LIKE %..% will be used)',
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CrawlerUAData::class
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webdl_crawltrackbundle_crawler_ua_data';
    }
}
