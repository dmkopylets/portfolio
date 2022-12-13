<?php

declare(strict_types=1);

namespace App\RacingData;

class SourcesFilesPropertyDto
{
    public string $folderName;
    public string $startLog;
    public string $finishLog;
    public string $abbreviation;
    public string $basePath;
    public string $dbDriver;
    public string $dbHost;
    public string $dbPort;
    public string $dbDatabase;
    public string $dbUserName;
    public string $dbPassword;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->folderName = $_ENV['RACING_DATAFILES_LOCATION'];
        $this->startLog = $_ENV['START_LOG'];
        $this->finishLog = $_ENV['FINISH_LOG'];
        $this->abbreviation = $_ENV['ABBREVIATION'];
        $this->basePath = $_ENV['BASE_PATH'];
        $this->dbDriver = $_ENV['DATABASE_DRIVER'];
        $this->dbHost = $_ENV['DATABASE_HOST'];
        $this->dbPort = $_ENV['DATABASE_PORT'];
        $this->dbDatabase = $_ENV['DATABASE_DATABASE'];
        $this->dbUserName = $_ENV['DATABASE_USERNAME'];
        $this->dbPassword = $_ENV['DATABASE_PASSWORD'];
    }
}
