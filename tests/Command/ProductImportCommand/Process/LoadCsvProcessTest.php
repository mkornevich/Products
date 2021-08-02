<?php


namespace App\Tests\Command\ProductImportCommand\Process;


use App\Command\ProductsImportCommand\FilesystemInterface;
use App\Command\ProductsImportCommand\Process\LoadCsvProcess;
use App\Command\ProductsImportCommand\ProductRow;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class LoadCsvProcessTest extends TestCase
{
    private LoadCsvProcess $process;

    private FilesystemInterface|MockObject $filesystem;

    private InputInterface|MockObject $input;

    private OutputInterface|MockObject $output;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(FilesystemInterface::class);

        $this->input = $this->createMock(InputInterface::class);
        $this->input->method('getArgument')->willReturn('test.csv');

        $this->output = $this->createMock(OutputInterface::class);

        $this->process = new LoadCsvProcess($this->filesystem);
    }

    private function csvToString(array $csv): string
    {
        return (new CsvEncoder())->encode($csv, 'csv', ['no_headers' => true]);
    }

    public function testProcessNormalLoad()
    {
        $csv = [
            ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
        ];

        $this->filesystem->method('fileGetContents')->willReturn($this->csvToString($csv));
        $this->filesystem->method('fileExists')->willReturn(true);

        $productRows = $this->process->process([], $this->input, $this->output);

        $this->assertCount(0, $this->process->getErrors());
        $this->assertCount(count($csv) - 1, $productRows);
        foreach ($productRows as $key => $productRow) {
            $this->assertEquals($csv[$key + 1], $productRow->csvRow);
        }
    }

    public function testProcessFileNotFound()
    {
        $this->filesystem->method('fileExists')->willReturn(false);

        $this->process->process([], $this->input, $this->output);

        $this->assertEquals(["File not found."], $this->process->getErrors());
    }

    public function testProcessTooManyColumns()
    {
        $csv = [
            ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', ''],
            ['', '', '', '', '', '', 'extra'],
        ];

        $this->filesystem->method('fileGetContents')->willReturn($this->csvToString($csv));
        $this->filesystem->method('fileExists')->willReturn(true);

        $this->process->process([], $this->input, $this->output);

        $this->assertEquals(
            ["In csv file, column count need to be " . ProductRow::COLUMNS_COUNT . "."],
            $this->process->getErrors());
    }

    public function testProcessTooFewRows()
    {
        $csv = [['h1', 'h2', 'h3', 'h4', 'h5', 'h6']];

        $this->filesystem->method('fileGetContents')->willReturn($this->csvToString($csv));
        $this->filesystem->method('fileExists')->willReturn(true);

        $this->process->process([], $this->input, new $this->output);

        $this->assertEquals(["In csv file, need to be at least 2 rows: header row and data rows."], $this->process->getErrors());
    }
}