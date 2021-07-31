<?php


namespace App\Command\ProductsImportCommand;


use App\Command\ProductsImportCommand\Process\Process;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductRowsProcessor
{
    /**
     * @var Process[]
     */
    private array $processes = [];

    public function addProcess(Process $process)
    {
        $this->processes[] = $process;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param EntityManagerInterface $entityManager
     */
    public function process(InputInterface $input, OutputInterface $output, EntityManagerInterface $entityManager)
    {
        $prevProductRows = [];
        foreach ($this->processes as $process)
        {
            $prevProductRows = $process->process($prevProductRows, $input, $output, $entityManager);
            if ($process->hasErrors()) {
                $output->writeln("Errors in process: " . get_class($process));
                OutputUtils::printNumerateMessages($output, $process->getErrors());
                return;
            }
        }
    }
}