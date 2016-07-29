<?php

namespace Buses\Parser;

class CenteredFigletParser implements FigletParserInterface
{
    protected $consoleWidth = 80;

    public function __construct($consoleWidth = 80)
    {
        $this->consoleWidth = $consoleWidth;
    }

    /**
     * @param string $text
     * @return string
     */
    public function parseText($text)
    {
        $lines = explode("\n", $text);

        array_walk($lines, function (&$line) {
            $missing = max(0, $this->consoleWidth - strlen($line));
            $line .= str_repeat(' ', $missing);
        });

        return implode("\n", $lines);
    }
}