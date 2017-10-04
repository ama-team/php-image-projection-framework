<?php

namespace AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Installer;

use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Fixture;
use Symfony\Component\Filesystem\Filesystem;

class Downloader
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function download(Fixture $fixture)
    {
        if ($this->filesystem->exists($fixture->getSource())) {
            return;
        }
        $resource = fopen($fixture->getUrl(), 'r');
        $this->filesystem->mkdir(dirname($fixture->getSource()));
        $this->filesystem->dumpFile($fixture->getSource(), $resource);
    }
}
