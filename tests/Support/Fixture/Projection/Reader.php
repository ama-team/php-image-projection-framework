<?php

namespace AmaTeam\Image\Projection\Test\Support\Fixture\Projection;

use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\Filesystem\Pattern;
use AmaTeam\Image\Projection\Geometry\Box;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Reader
{
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;
    /**
     * @var string
     */
    private $workspace;

    public function __construct($workspace)
    {
        $this->workspace = $workspace;
        $this->denormalizer = new ObjectNormalizer();
    }

    public function readAll(array $definitions)
    {
        return array_map([$this, 'read'], $definitions);
    }

    private function read(array $definition)
    {
        $denormalizer = $this->denormalizer;
        /** @var Fixture $fixture */
        $fixture = $denormalizer->denormalize($definition, Fixture::class);
        $fixture->setId(md5($fixture->getUrl()));
        $defaultFace = MappingInterface::DEFAULT_FACE;
        $faces = $fixture->getFaces() ?: [$defaultFace => ['x' => 0, 'y' => 0]];
        $faces = array_map(function ($data) use ($denormalizer) {
            return $denormalizer->denormalize($data, Offset::class);
        }, $faces);
        $fixture->setFaces($faces);
        /** @var Box $size */
        $size = $denormalizer->denormalize($fixture->getSize(), Box::class);
        $fixture->setSize($size);
        $layout = $fixture->getLayout() ?: ['width' => 1, 'height' => 1];
        /** @var Box $layout */
        $layout = $denormalizer->denormalize($layout, Box::class);
        $fixture->setLayout($layout);
        $workspace = self::getWorkspace($fixture);
        $fixture->setWorkspace($workspace);
        $pattern = $workspace . Fixture::PATTERN_SUFFIX;
        $fixture->setPattern(new Pattern($pattern));
        $fixture->setSource($workspace . Fixture::SOURCE_PATH_SUFFIX);
        return $fixture;
    }

    private function getWorkspace(Fixture $fixture)
    {
        $segments = [
            $this->workspace,
            Fixture::INSTALLATION_PATH,
            $fixture->getId()
        ];
        return implode(DIRECTORY_SEPARATOR, $segments);
    }
}
