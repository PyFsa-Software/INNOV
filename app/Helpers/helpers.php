<?php

function convertDigitsToWord(int $number): string
{
    $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return $f->format($number);

}