<?php

namespace App\Command;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use League\Csv\Reader;

class ImportData extends Command
{
    protected static $defaultName = 'app:import-run';
    private $em;

    public function __construct(
        string $name = null,
        EntityManagerInterface $em
    ) {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Import daily run.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to import daily run from SIRENE DB...')
            ->addArgument('date', InputArgument::REQUIRED, 'The run date.')        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $zipFile = 'sirene_'.$input->getArgument('date').'_E_Q.zip';
        $input = $this->container->getParameter('app.api_uri') . $zipFile;

        $client = new \GuzzleHttp\Client();

        $dir =  __DIR__ .'/../../downloads' ;

        if (!file_exists($dir)) {
            throw new FileNotFoundException($dir);
        }
        $dest = $dir .'/' .$zipFile;

        try {
            $request = new \GuzzleHttp\Psr7\Request('GET', $input) ;
            echo "\n Try to download $input";
            $promise = $client->sendAsync($request)->then(function ($response) use ($zipFile, $dest, $dir) {

                file_put_contents($dest, $response->getBody());
                $zip = new \ZipArchive;
                if ($zip->open($dest)) {
                    $zip->extractTo($dir);

                    // assuming only one file per zip
                    $extractedFile = $zip->getNameIndex(0);
                    $zip->close();

                    echo "\n Successfuly unzip : \n " . $zipFile  . "\n" . $extractedFile ."\n";
                    echo "\n Removing ZIP " . $zipFile;
                    unlink($dir."/".$zipFile);

                    // extract fileinfo
                    $pathParts = pathinfo($dir.'/'.$extractedFile);
                    $filename  = $pathParts['filename'];
                    $ext =  $pathParts['extension'];
                    $cp1252_file = $dir.'/'.$filename.'.'.$ext;
                    $utf8_csv = $dir.'/'.$filename."_utf8.".$ext;

                    // converting to UTF-8
                    $data = iconv(
                        "CP1252",
                        "UTF-8",
                        file_get_contents($cp1252_file)
                    );

                    // creating a new file utf8
                    file_put_contents($utf8_csv, $data);

                    unlink($dir.'/'.$filename.'.'.$ext);

                    echo "\n insert CSV data in MySQL ... " ;

                    $nbRows = $this->_InsertCsvToDB($utf8_csv);

                    echo "\n $nbRows rows inserted";
                } else {
                    echo "\n Failed to unzip " . $zipFile;
                }
            });

            $promise->wait();
        } catch (\Throwable $t) {
            echo "Something wrong happened";
            echo $t->getMessage();
        }

        echo "\n execution finished";
        return 0;
    }

    /**
     * Insert Data in DB and returns number of rows inserted
     * @param $filePath
     * @return int
     */
    private function _InsertCsvToDB($filePath):int
    {

        $csv = Reader::createFromPath($filePath, 'r')
                       ->setOutputBOM(Reader::BOM_UTF8)
                       //->addStreamFilter('convert.iconv.ISO-8859-1/UTF-8')
                       ->setHeaderOffset(0)
                       ->setDelimiter(';')
                       ->setEnclosure('"')
        ;

        // set fields we insert in DB
        $fields = ['SIREN','L1_DECLAREE','L2_DECLAREE','L4_NORMALISEE','L6_NORMALISEE','L7_NORMALISEE','LIBTEFEN'];
        $i = 0;
        foreach ($csv as $record) {
            $company = new Company();
            foreach ($fields as $field) {
                $dbField = str_replace('_', '', $field);
                $method = 'set'.ucwords(mb_strtolower($dbField));
                $company->$method($record[$field]);
            }
            //TODO: Verify record does not already exist in DB
            // If record exsists then delete ?
            $this->em->persist($company);
            $i++;
        }

        $this->em->flush();
        return $i;
    }
}
