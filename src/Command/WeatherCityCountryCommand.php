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
    name: 'weather:city_country',
    description: 'Add a short description for your command',
)]
class WeatherCityCountryCommand extends Command
{
    public function __construct(
        private LocationRepository $locationRepository,
        private WeatherUtil $weatherUtil

    )

    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
        ->addArgument('country', InputArgument::REQUIRED, 'Country code')
        ->addArgument('city', InputArgument::REQUIRED, 'City name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');
        $location = $this->locationRepository->findOneBy(['country' => $country, 'city' => $city]);
        if (!$location) {
            $io->error('Location not found');
            return Command::FAILURE;
        }
        $weather = $this->weatherUtil->getWeatherForLocation($location);

        //Print on console
        $io->writeln("Location: {$location->getCountry()} {$location->getCity()}");
        foreach($weather as $row){
            $io->writeln("{$row->getDate()} {$row->getTemperature()} C {$row->getHumidity()}%");
        }
        return Command::SUCCESS;
    }
}
