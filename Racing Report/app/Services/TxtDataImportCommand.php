<?php

declare(strict_types=1);

namespace App\Services;


use App\RacingData\FileReaderDrivers;
use App\RacingData\FileReaderLogs;
use App\RacingData\ScoreCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TxtDataImportCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('app:import')
            ->setDescription('Report of Monaco 2018 Racing data importer')
            ->setHelp('This command for import data from txt-files into database');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(messages: [
            '<info>======          Racing Report data importing App               =====</>',
            '',
        ]);

        $setup = new SourceInitService();
        $connectionDB = new ConnectDB;
        $connectionDB->bootEloquent($setup);

        if (!$setup->SoucesFilesIsExist()) {
            $output->writeln(messages: [
                '<info>================   These files are missing:   ======================</>'
                ]);
            foreach ($setup->getAbsentsFilesList() as $value){
                $output->writeln(messages: [$value]);
            }
            return Command::FAILURE;
        } else {
            $driversReader = new FileReaderDrivers;
            $drivers = $driversReader->read($setup->getSourcesFilesPropertyDto());
            $saver = new ExportToDbDrivers();
            $saver->store($drivers);
            $output->writeln(messages: [
                '<info>================    Drivers info imported     ======================</>'
            ]);
            $flightsReader = new FileReaderLogs;
            $flights = $flightsReader->read($setup->getSourcesFilesPropertyDto());
            $saver = new ExportToDbFlights;
            $saver->store($flights);
            $output->writeln(messages: [
                '<info>================    Flights info imported     ======================</>'
            ]);
            $racing = new ScoreCalculator;
            $racing->run();
            return Command::SUCCESS;
        }
    }
}
