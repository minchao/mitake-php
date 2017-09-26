<?php

namespace Mitake\Console;

use Mitake\Client;
use Symfony\Component\Console\Input\InputInterface;

trait ClientTrait
{
    /**
     * @param InputInterface $input
     * @return Client
     */
    public function createClient(InputInterface $input)
    {
        $credentials = $input->getOption('credentials');
        if (is_null($credentials)) {
            $username = $input->getOption('username');
            $password = $input->getOption('password');
        } else {
            $file = file_get_contents($credentials);
            $json = json_decode($file, true);
            if (is_null($json) || !isset($json['username']) || !isset($json['password'])) {
                throw new \InvalidArgumentException('Invalid credentials');
            }

            $username = $json['username'];
            $password = $json['password'];
        }

        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('The --username and --password are required');
        }

        return new Client($username, $password, new \GuzzleHttp\Client());
    }
}
