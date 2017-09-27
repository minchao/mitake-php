<?php

namespace Mitake\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Application
 * @package Mitake\Console
 */
class App extends Application
{
    public function __construct()
    {
        parent::__construct('Developer Tools');

        $this->getDefinition()->addOptions([
            new InputOption(
                '--username',
                '-u',
                InputOption::VALUE_OPTIONAL,
                'Mitake Username'
            ),
            new InputOption(
                '--password',
                '-p',
                InputOption::VALUE_OPTIONAL,
                'Mitake Password'
            ),
            new InputOption(
                '--credentials',
                '-c',
                InputOption::VALUE_OPTIONAL,
                'Mitake Credentials'
            ),
        ]);

        $this->addCommands([
            new Command\Balance(),
            new Command\Send(),
            new Command\MessageStatus(),
        ]);
    }
}
