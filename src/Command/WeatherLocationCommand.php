<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:location',
    description: 'Add a short description for your command',
)]
class WeatherLocationCommand extends Command
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
        private readonly LocationRepository $locationRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
          ->addArgument('id', InputArgument::REQUIRED, 'Location ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $id = (int) $input->getArgument('id');
        $location = $this->locationRepository->find($id);
        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        //Print response to console
        $io->writeln("Location: {$location->getCountry()} {$location->getCity()}");
        foreach ($measurements as $measurement) {
            $io->writeln("{$measurement->getDate()} {$measurement->getTemperature()} C {$measurement->getHumidity()}%");
        }

        return Command::SUCCESS;
    }
}
