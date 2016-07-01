<?php

namespace Inachis\Tests\CoreBundle\File;

use Inachis\Component\CoreBundle\File\FileHandler;
use Inachis\Component\CoreBundle\Exception\RouteConfigException;

/**
 * @Entity
 * @group unit
 */
class FileHandlerTest extends \PHPUnit_Framework_TestCase
{
    public $fileHandler;

    public function setUp()
    {
        $this->fileHandler = new FileHandler();
    }

    public function testGetAllFilesInFolder()
    {
        $this->assertTrue(!empty($this->fileHandler->getAllFilesInFolder('.')));
    }

    public function testGetAllFilesInFolderOfType()
    {
        $files = $this->fileHandler->getAllFilesInFolderOfType('.', 'md');
        $this->assertTrue(!empty($files));
        $this->assertGreaterThan(0, strpos($files[0], '.md'));
    }

    public function testLoadFileWithDirectoryTraversal()
    {
        try {
            $this->fileHandler->loadFile('./../', 'FileDoesntExist.md');
        } catch (\Exception $exception) {
            $this->assertContains('Directory traversal', $exception->getMessage());
        }
    }

    public function testLoadFileFailure()
    {
        try {
            $this->fileHandler->loadFile('.', 'FileDoesntExist.md');
        } catch (\Exception $exception) {
            $this->assertContains('Failed', $exception->getMessage());
        }
    }

    public function testLoadFile()
    {
        $this->assertTrue(!empty($this->fileHandler->loadFile('.', 'README.md')));
    }
}
