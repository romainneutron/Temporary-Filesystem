<?php

/*
 * This file is part of TemporaryFilesystem.
 *
 * (c) Romain Neutron <imprec@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Neutron\TemporaryFilesystem;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class TemporaryFilesystem
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Get an array of temporary files.
     *
     * Temporary files are created inside the system temporary folder. You must
     * removed them manually at the end of use.
     *
     * @param integer $quantity  The quantity of temporary files requested
     * @param string  $prefix    The prefix of the files
     * @param string  $suffix    The suffix of the files
     * @param string  $extension The extension of the files
     * @param integer $maxTry    The maximum number of trials to create one temporary file
     *
     * @return array An array of filenames
     *
     * @throws \InvalidArgumentException In case you provide a wrong argument
     * @throws IOException               In case of failure
     */
    public function createTemporaryFiles($quantity = 1, $prefix = null, $suffix = null, $extension = null, $maxTry = 65536)
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Invalid temporary files quantity');
        }

        $files = array();

        while ($quantity > 0) {
            $files[] = $this->createEmptyFile(sys_get_temp_dir(), $prefix, $suffix, $extension, $maxTry);
            $quantity --;
        }

        return $files;
    }

    /**
     * Create an empty file in the specified directory.
     *
     * The new file is created in the requested directory and will fit the
     * the given parameters. Please note that the filename contains some
     * random caracters.
     *
     * @param  string  $basePath  The directory where to create the file
     * @param  string  $prefix    The prefix of the file
     * @param  string  $suffix    The suffix of the file
     * @param  string  $extension The extension of the file
     * @param  integer $maxTry    The maximum number of trials to create the file
     *
     * @return string  The path of the created file
     *
     * @throws IOException in case of failure
     */
    public function createEmptyFile($basePath, $prefix = null, $suffix = null, $extension = null, $maxTry = 65536)
    {
        if (false === is_dir($basePath) || false === is_writeable($basePath)) {
            throw new IOException(sprintf('`%s` should be a writeable directory', $basePath));
        }

        if ($suffix === null && $extension === null) {
            if (false === $file = @tempnam($basePath, $prefix)) {
                throw new IOException('Unable to generate a temporary filename');
            }

            return $file;
        }

        while ($maxTry > 0) {
            $file = $basePath . DIRECTORY_SEPARATOR
                . $prefix . base_convert(mt_rand(0x19A100, 0x39AA3FF), 10, 36) . $suffix
                . ( $extension ? '.' . $extension : '');

            if (false === file_exists($file)) {
                $this->filesystem->touch($file);

                return $file;
            }

            $maxTry --;
        }

        throw new IOException('Unable to generate a temporary filename');
    }

    /**
     * Creates a TemporaryFilesystem
     *
     * @param Filesystem $filesystem
     *
     * @return TemporaryFilesystem
     */
    public static function create(Filesystem $filesystem)
    {
        return new static($filesystem);
    }
}
