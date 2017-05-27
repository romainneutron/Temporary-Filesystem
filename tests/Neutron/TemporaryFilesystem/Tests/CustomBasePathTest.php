<?php

namespace Neutron\TemporaryFilesystem\Tests;

use Neutron\TemporaryFilesystem\TemporaryFilesystem;

class CustomBasePathTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->workspace = DEV_VAR_DIR.DIRECTORY_SEPARATOR.time().mt_rand(0, 1000);
        mkdir($this->workspace, 0777, true);
        $this->workspace = realpath($this->workspace);
        $this->filesystem = TemporaryFilesystem::create($this->workspace);
    }

    public function testCreateTemporaryDir()
    {
        $dir = $this->filesystem->createTemporaryDirectory();
        $this->assertStringStartsWith(DEV_VAR_DIR, $dir);
    }

    public function testTemporaryFile()
    {
        $file = $this->filesystem->createTemporaryFile();
        $this->assertStringStartsWith(DEV_VAR_DIR, $file);
    }
}
