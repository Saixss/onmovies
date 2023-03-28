<?php


namespace App\Service;


class UrlEncoder
{
    public function encode(string $value)
    {
        // Replaces any characters that aren't letter or a number with a '-'
        $valueEncoded =  preg_replace('/[^a-z0-9]+/', '-', strtolower($value));

        // Removes last character that isn't letter or a digit
        return preg_replace('/[^a-z0-9]$/', '', $valueEncoded);
    }
}