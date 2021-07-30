<?php


namespace App\Command\ProductsImportCommand;


use Symfony\Component\Console\Output\OutputInterface;

class OutputUtils
{
    public static function printNumerateMessages(OutputInterface $output, array $messages)
    {
        foreach ($messages as $number => $message) {
            $output->writeln($number + 1 . ') ' . $message);
        }
    }
}