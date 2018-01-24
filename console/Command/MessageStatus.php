<?php

namespace Mitake\Console\Command;

use Mitake\Console\ClientTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MessageStatus
 * @package Mitake\Console\Command
 */
class MessageStatus extends Command
{
    use ClientTrait;

    protected function configure()
    {
        $this->setName('status')
            ->setDescription('Fetch the status of specific messages')
            ->addOption(
                '--msgids',
                '-m',
                InputOption::VALUE_REQUIRED,
                'Message IDs, for example: 1000000000,1000000001'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (empty($input->getOption('msgids'))) {
            throw new \InvalidArgumentException('The --msgids is required');
        }

        $ids = explode(',', $input->getOption('msgids'));

        $client = $this->createClient($input);
        $resp = $client->queryMessageStatus($ids);

        $output->writeln(json_encode($resp->toArray(), JSON_PRETTY_PRINT));
    }
}
