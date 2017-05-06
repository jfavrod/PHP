<?php
require pathinfo(__DIR__)['dirname'] . '/vendor/autoload.php';
use Epoque\PHP\FileReader;
use PHPUnit\Framework\TestCase;


define('TEST_FILE', pathinfo(__DIR__)['dirname'] . '/test/testdir/file0');


class FileReaderTest extends TestCase
{
    public function testRead()
    {
        $reader = new FileReader(TEST_FILE);
        $this->assertEquals('H', $reader->read());
    }


    public function testReadln()
    {
        $reader = new FileReader(TEST_FILE);
        $this->assertEquals('Hello World', $reader->readln());
    }


    public function testReadWholeFile()
    {
        $reader = new FileReader(TEST_FILE);
        $file = '';

        while ($c = $reader->read()) {
            $file .= $c;
        }

        $this->assertEquals($file, file_get_contents(TEST_FILE));
    }
}
