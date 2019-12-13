<?php

namespace App\Command;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportData extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import-data';
    private $em;

    public function __construct(string $name = null,EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->em = $em;
    }

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
        $url = 'http://files.data.gouv.fr/sirene/';
        $date = "2018088";
        $fname = 'sirene_'.$date.'_E_Q.zip';
        $input = $url . $fname;

        $client = new \GuzzleHttp\Client();

        try{
            $request = new \GuzzleHttp\Psr7\Request('GET', $input) ;
            echo "\n Try to download $input";
            /*$promise = $client->sendAsync($request)->then(function ($response) use ($fname) {

                $dir =  __DIR__ .'/../../downloads' ;

                if (!file_exists($dir)){
                   throw new FileNotFoundException($dir);
                }
                $dest = $dir .'/' .$fname;
                file_put_contents  ($dest,$response->getBody());

                $zip = new \ZipArchive;
                if ($zip->open($dest)) {
                    $zip->extractTo($dir);

                    // assuming only one file per zip
                    $filename = $zip->getNameIndex(0);
                    $zip->close();

                    echo "\n Successfuly unzipped : " . $fname  . " to: " . $filename;
                    echo "\n Removing ZIP " . $fname;

                } else {
                    echo "\n Failed to unzip " . $fname;
                }

            });

            $promise->wait();*/

            $this->_InsertCsvToDB();


        } catch(\Throwable $t){
            echo "Something wrong happened";
            echo $t->getMessage();
        }

        return 0;
    }

    private function _InsertCsvToDB($file=null){

        $csv = Reader::createFromPath(__DIR__ .'/../../downloads/test.csv' , 'r')
                       ->setOutputBOM(Reader::BOM_UTF8)
                       ->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8')
                       ->setHeaderOffset(0)
                       ->setDelimiter(';')
                       ->setEnclosure('"')
        ;


        // set fields we insert in DB
        $fields = ['SIREN','L1_DECLAREE','L2_DECLAREE','L4_NORMALISEE','L6_NORMALISEE','L7_NORMALISEE','LIBTEFEN'];

        foreach ($csv as $record) {
            $company = new Company();
            foreach ($fields as $field){
                $dbField = str_replace('_', '', $field);
                $method = 'set'.ucwords(mb_strtolower($dbField));
                $company->$method($record[$field]);
            }
            $this->em->persist($company);
        }

        $this->em->flush();
    }



}