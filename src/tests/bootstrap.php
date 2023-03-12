<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (!isset($_SERVER['APP_ENV'])) {
    throw new Exception('No environment set');
}

$environment = $_SERVER['APP_ENV'];

$kernel = new Kernel($environment, true);
$kernel->boot();

$application = new Application($kernel);

$command = $application->find('doctrine:database:drop');
$input = new ArrayInput(['--force' => true]);
$command->run($input, new ConsoleOutput());

$command = $application->find('doctrine:database:create');
$input = new ArrayInput([]);
$command->run($input, new ConsoleOutput());

$command = $application->find('doctrine:schema:create');
$input = new ArrayInput([]);
$command->run($input, new ConsoleOutput());
