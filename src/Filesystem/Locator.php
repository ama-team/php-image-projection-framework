<?php

namespace AmaTeam\Image\Projection\Filesystem;

use League\Flysystem\FilesystemInterface;

class Locator
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param Pattern $pattern
     * @return array
     */
    public function locate(Pattern $pattern)
    {
        $root = $pattern->getFixedPart();
        // todo: quick hack instead of proper solution
        if ($pattern->isFixed()) {
            $root = dirname($root);
        }
        $entries = [];
        foreach ($this->filesystem->listContents($root, true) as $entry) {
            if (!$pattern->matches($entry['path'])) {
                continue;
            }
            $entry['parameters'] = $pattern->getParameters($entry['path']);
            $entries[] = $entry;
        }
        return $entries;
    }
}
