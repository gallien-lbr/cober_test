<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportData extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import-data';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Import data.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to import data...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $f = 'http://files.data.gouv.fr/sirene/sirene_2018088_E_Q.zip';
        //$f = 'http://httpbin.org';
        $client = new \GuzzleHttp\Client();

        try{
            $request = new \GuzzleHttp\Psr7\Request('GET', $f) ;
            $promise = $client->sendAsync($request)->then(function ($response) {
                echo 'I completed! ' ;//. $response->getBody();
            });

            $promise->wait();

        }catch(\Throwable $t){
            echo "Something wrong happened";
        }

        return 0;
    }
}