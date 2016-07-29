<?php

namespace Buses\Command;

use Buses\Parser\ApiParser;
use Buses\Service\ApiService;
use Buses\Service\EventService;
use Buses\View\ConsoleView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class BusStopCommand extends Command
{
    protected function configure()
    {
        $this->setName('buses:service:next_bus')
            ->setDescription('Displays the next bus for a given bus stop.')
            ->addOption('stop', null, InputOption::VALUE_REQUIRED, 'The id of the bus stop')
            ->addOption('width', null, InputOption::VALUE_OPTIONAL, 'The width of the console', 80);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $view = new ConsoleView($output, $input->getOption('width'));

        $stop = $input->getOption('stop');

        if (!$stop) {
            $view->render(['simple_error' => 'You have not specified a bus stop ID']);
            return;
        }

        $api = new ApiService();
        $api->setStop($stop);
        $parser = new ApiParser();
        $eventService = new EventService();

        while (true)
        {
            $this->process($api, $parser, $eventService, $view);
            sleep(55);
        }
    }
    
    private function process(ApiService $api, ApiParser $parser, EventService $eventService, ConsoleView $view)
    {
        try {
            $response = $api->getData();
        } catch (\Exception $e) {
            $response = null;
        }

        $events = $eventService->getCurrentEvents();

        $params = [];
        if (count($events)) {
            $params['events'] = $events;
        }

        $view->clear();
        if ($response === null) {
            $params['error'] = 'No bus';
            $view->render($params);
            return;
        }

        $decoder = new JsonDecode();
        $data = $decoder->decode($response, JsonEncoder::FORMAT);

        $outputData = $parser->parse($data);

        if ($outputData === null) {
            $params['error'] = 'No bus';
        } else {
            $params += (array) $outputData;
        }

        $view->render($params);
    }
}