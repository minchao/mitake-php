<?php

namespace Mitake\Console\Command;

use Mitake\Console\ClientTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Balance
 * @package Mitake\Console\Command
 */
class Balance extends Command
{
    use ClientTrait;

    protected function configure()
    {
        $this->setName('balance')
            ->setDescription('Retrieve your account balance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->createClient($input);

        $output->writeln($client->getAPI()->queryAccountPoint());
    }
}
