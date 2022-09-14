<?php

declare(strict_types=1);

namespace App;

use App\Reporter\CliReporter;
use App\Reporter\HtmlReporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RacingCommand extends Command
{
    public function configure(): void
    {
        $this->setName('app:report')
            ->setDescription('Report of Monaco 2018 Racing')
            ->setHelp('This command reverses Top 15 cars are going to the Q2 stage')
            ->addOption(
                'driver',
                'd',
                InputOption::VALUE_REQUIRED,
                'Pass which driver are you interested in there',
                ''
            )
            ->addOption(
                'files',
                'f',
                InputOption::VALUE_REQUIRED,
                'Pass path to file with statistics there',
                ''
            )
            ->addOption(
                'asc',
                '',
                InputOption::VALUE_NONE,
                'shows list of drivers on DESCENDING order. (default order is asc)'
            )
            ->addOption(
                'desc',
                '',
                InputOption::VALUE_NONE,
                'shows list of drivers on DESCENDING order. (default order is asc)'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            '<info>======              Racing Report Console App                  =====</>',
            '<info>====================================================================</>',
            '',
        ]);
        $logsLocation = $input->getOption('files');
        if ($this->filesExist($logsLocation, $output)) {
            $builder = new BuildDataReport();
            $racingData = $builder->buildReport($logsLocation);
            $cliReport = new CliReporter();
            $htmlReport = new HtmlReporter();

            if ($input->getOption('driver')) {
                $cliReport->printOne($racingData, $output, $input->getOption('driver'));
            } else {
                $descending = false;
                if ($input->getOption('desc') === true) {
                    $descending = true;
                }
                $cliReport->print($racingData, $output, $descending);
                $htmlReport->print($racingData, $logsLocation, $descending);
            }
            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }
    }

    private function filesExist(string $logsLocation, OutputInterface $output): bool
    {
        $fileName = '';
        $result = true;
        if (!file_exists($logsLocation . '/start.log')) {
            $fileName = 'start.log';
            $result = false;
        }
        if (!file_exists($logsLocation . '/end.log')) {
            $fileName = 'end.log';
            $result = false;
        }
        if (!file_exists($logsLocation . '/abbreviations.txt')) {
            $fileName = 'abbreviations.txt';
            $result = false;
        }
        if (!$result) {
            $output->writeln('file ' . $fileName . ' does`t exists in folder  "' . $logsLocation);
        }
        return $result;
    }
}
