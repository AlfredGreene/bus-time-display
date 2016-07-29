<?php

namespace Buses\View;

use Buses\Model\Event;
use Buses\Parser\CenteredFigletParser;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleView
{
    private $output;
    private $figletParser;
    
    private $font = 'univers';

    /**
     * ConsoleView constructor.
     * @param OutputInterface $outputInterface
     * @param int $consoleWidth
     */
    public function __construct(OutputInterface $outputInterface, $consoleWidth)
    {
        $this->output = $outputInterface;
        $this->figletParser = new CenteredFigletParser($consoleWidth);
        $this->output->getFormatter()->setStyle('success', new OutputFormatterStyle('white', 'green'));
        $this->output->getFormatter()->setStyle('event', new OutputFormatterStyle('white', 'cyan'));
    }
    
    public function render(array $params)
    {
        $helper = new FormatterHelper();
        $messages = [];

        if (isset($params['error'])) {
            $messages[] = $helper->formatBlock($this->generateAsciiAsArray($params['error']), 'error', true);
        } else if (isset($params['simple_error'])) {
            $messages[] = $helper->formatBlock($params['simple_error'], 'error', true);
        } else {
            $messages[] = $helper->formatBlock($this->generateAsciiAsArray('Next bus'), 'success', true);
            $messages[] = $helper->formatBlock($this->generateAsciiAsArray($params['destination']), 'success', true);
            $messages[] = $helper->formatBlock($this->generateAsciiAsArray($params['time']), 'success', true);
        }

        if (isset($params['events'])) {
            foreach ($params['events'] as $event) {
                /** @var $event Event */
                $messages[] = $helper->formatBlock($this->generateAsciiAsArray($event->getDescription()), 'event', true);
            }
        }

        $this->output->write($messages, true);
    }

    public function clear()
    {
        $this->output->write(sprintf("\033\143"));
    }
    
    private function generateAsciiAsArray($string)
    {
        return explode("\n", $this->generateAsciiAsString($string));
    }

    private function generateAsciiAsString($string)
    {
        return $this->figletParser->parseText(
            shell_exec(
                escapeshellcmd('figlet -ctf ' . $this->font . ' ' . escapeshellarg($string)
                )
            )
        );
    }
}