<?php

namespace AmaTeam\Image\Projection\Test\Support\Fixture\Projection;

use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Installer\Downloader;
use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Installer\Splitter;
use Imagine\Gd\Imagine;
use Imagine\Image\ImagineInterface;
use Symfony\Component\Filesystem\Filesystem;

class Installer
{
    /**
     * @var Downloader
     */
    private $downloader;
    /**
     * @var Splitter
     */
    private $splitter;

    public function __construct(
        ImagineInterface $imagine = null,
        Filesystem $filesystem = null
    ) {
        $filesystem = $filesystem ?: new Filesystem();
        $imagine = $imagine ?: new Imagine();
        $this->downloader = new Downloader($filesystem);
        $this->splitter = new Splitter($imagine, $filesystem);
    }

    public function install(Fixture $fixture)
    {
        $this->downloader->download($fixture);
        $this->splitter->split($fixture);
        return $fixture;
    }
}
