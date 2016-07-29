<?php

namespace Buses\Parser;

interface FigletParserInterface
{
    /**
     * Parses the output of the figlet command and adds the correct number of spaces to the end of each
     * line of text so it can be displayed correctly.
     * @param string $text
     * @return string
     */
    public function parseText($text);
}