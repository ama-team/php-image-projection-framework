<?php

namespace AmaTeam\Image\Projection\Test\Support\Allure;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\Image\Format;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use Symfony\Component\Filesystem\Filesystem;
use Yandex\Allure\Adapter\Support\AttachmentSupport;

class TileAttachmentListener implements ListenerInterface
{
    use AttachmentSupport;

    public function accept(
        TileInterface $tile,
        SpecificationInterface $specification = null
    ) {
        $filesystem = new Filesystem();
        $temporaryFile = $filesystem->tempnam(sys_get_temp_dir(), 'pf-');
        $content = $tile->getImage()->getBinary(Format::JPEG);
        $filesystem->dumpFile($temporaryFile, $content);
        $caption = (string) $tile->getPosition();
        $this->addAttachment($temporaryFile, $caption, 'image/jpeg');
    }
}
