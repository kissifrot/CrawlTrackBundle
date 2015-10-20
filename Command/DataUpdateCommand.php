<?php
/**
 * Data update command, used to update the reference crawler data
 */

namespace WebDL\CrawltrackBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataUpdateCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this->setName('crawltrack:update-data')
            ->setDescription('Updates Crawltrack reference data');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

    }
}