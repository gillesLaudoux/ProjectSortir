<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


/** composer require symfony/serializer nécessaire pour lire un CSV */
class UpdateUserCommand extends Command
{
    protected static $defaultName = "app:upload-user";

    protected function configure()
    {
        $this->setDescription("Pour mettre à jour nos utilisateurs à l'aide d'un fichier csv")
            ->addArgument('nameFile', InputArgument::NEEDED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nameFile = $this->getArgument('nameFile');

        /* chemin du fichier à charger */
        $pathCSvFile = "C:\Users\glaudoux2021\PhpstormProjects\ProjectSortir\public\CSV/".$nameFile."csv";

        /* Création d'un decoder afin de lire un fichier */
        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);


        $rows = $decoder->decode(file_get_contents($pathCSvFile), "csv"); // récupére les lignes du fichier CSV

        dd($rows);

    }
}