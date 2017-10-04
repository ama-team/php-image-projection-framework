<?php

namespace AmaTeam\Image\Projection\Test\Suite\Acceptance;

use AmaTeam\Image\Projection\Framework;
use AmaTeam\Image\Projection\Framework\Listener\SaveListener;
use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Image\Adapter\Discovery;
use AmaTeam\Image\Projection\Image\Manager as ImageManager;
use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Test\Support\Allure\TileAttachmentListener;
use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Fixture;
use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Manager;
use AmaTeam\Image\Projection\Test\Support\Structure;
use AmaTeam\Image\Projection\Tile\Loader;
use AmaTeam\Image\Projection\Type\CubeMap\Handler as CubeMap;
use AmaTeam\Image\Projection\Type\Equirectangular\Handler as Equirectangular;
use AmaTeam\Image\Projection\Type\Registry;
use Codeception\Test\Unit;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Ramsey\Uuid\Uuid;
use Yandex\Allure\Adapter\Support\StepSupport;

class ConversionTest extends Unit
{
    use StepSupport;

    /**
     * @var Framework
     */
    private $framework;
    /**
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var Manager
     */
    private $imageManager;
    /**
     * @var Loader
     */
    private $loader;
    /**
     * @var TileAttachmentListener
     */
    private $attachmentListener;

    protected function _before()
    {
        $adapter = new Local(Structure::getProjectRoot());
        $this->filesystem = new Filesystem($adapter);
        $registry = (new Registry($this->filesystem))->registerDefaultTypes();
        $this->framework = new Framework($registry);
        $this->imageManager = new ImageManager($this->filesystem, Discovery::find());
        $this->loader = new Loader($this->imageManager, $this->filesystem);
        $this->attachmentListener = new TileAttachmentListener();
    }

    public function dataProvider()
    {
        $this->_before();
        $fixtures = (new Manager())->enumerate();
        $registry = $this->framework->getRegistry();
        $types = $registry->getRegisteredTypes();
        $result = [];
        /** @var Fixture $fixture */
        foreach ($fixtures as $fixture) {
            foreach ($types as $type) {
                if ($registry->findType($fixture->getType()) === $type) {
                    continue;
                }
                $result[] = [$fixture, $type];
            }
        }
        return $result;
    }

    /**
     * @param Fixture $fixture
     * @param $type
     *
     * @dataProvider dataProvider
     */
    public function testConversion(Fixture $fixture, $type)
    {
        if (!$this->framework->getRegistry()->exists($fixture->getType())) {
            $message = "Type {$fixture->getType()} is not yet supported";
            /** @noinspection PhpUndefinedMethodInspection */
            $this->markTestSkipped($message);
            return;
        }
        $this->executeStep('Save source tiles', function () use ($fixture) {
            foreach ($this->loader->load($fixture->getPattern()) as $tile) {
                $this->attachmentListener->accept($tile);
            }
        });
        $id = Uuid::uuid4();
        $encodedPattern = "tests/Metadata/Artifacts/$id/e/{face}/{y}/{x}.jpg";
        $decodedPattern = "tests/Metadata/Artifacts/$id/d/{face}/{y}/{x}.jpg";
        $source = (new Specification())
            ->setType($fixture->getType())
            ->setPattern($fixture->getPattern());
        $encoded = (new Specification())
            ->setType($type)
            ->setPattern($encodedPattern)
            ->setTileSize($this->getTileSize($type));
        $this->executeStep('Encoding', function () use ($source, $encoded) {
            $this
                ->framework
                ->createConversion($source, $encoded)
                ->addListener($this->attachmentListener)
                ->addListener(new SaveListener())
                ->run();
        });
        $decoded = (new Specification())
            ->setType($fixture->getType())
            ->setPattern($decodedPattern)
            ->setTileSize($source->getTileSize());
        $this->executeStep('Decoding', function () use ($encoded, $decoded) {
            $this
                ->framework
                ->createConversion($encoded, $decoded)
                ->addListener($this->attachmentListener)
                ->addListener(new SaveListener())
                ->run();
        });
        // TODO: compare resulting images
    }

    private function getTileSize($type)
    {
        $registry = $this->framework->getRegistry();
        $class = get_class($registry->getHandler($type));
        switch ($class) {
            case CubeMap::class:
                return new Box(512, 512);
            case Equirectangular::class:
                return new Box(1024, 512);
            default:
                return null;
        }
    }
}
