<?php

namespace Neutron\TemporaryFilesystem\Tests;

use Neutron\TemporaryFilesystem\TemporaryFilesystem;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string $workspace
     */
    protected $workspace = null;

    /**
     * @var TemporaryFilesystem
     */
    protected $filesystem;

    public function tearDown()
    {
        $this->clean($this->workspace);
    }

    /**
     * @param string $file
     */
    protected function clean($file)
    {
        if (is_dir($file) && !is_link($file)) {
            $dir = new \FilesystemIterator($file);
            foreach ($dir as $childFile) {
                $this->clean($childFile);
            }

            rmdir($file);
        } else {
            unlink($file);
        }
    }
}
