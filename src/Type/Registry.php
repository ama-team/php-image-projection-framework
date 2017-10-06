<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\Image\Adapter\Discovery;
use AmaTeam\Image\Projection\API\Image\ImageFactoryInterface;
use AmaTeam\Image\Projection\Filesystem\Factory;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\API\Type\HandlerInterface;
use AmaTeam\Image\Projection\Type\CubeMap\Handler as CubeMapHandler;
use AmaTeam\Image\Projection\Type\Equirectangular\Handler as EquirectHandler;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;

class Registry
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var Manager
     */
    private $imageManager;
    /**
     * @var HandlerInterface[]
     */
    private $registry = [];
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FilesystemInterface $filesystem
     * @param ImageFactoryInterface $imageFactory
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        FilesystemInterface $filesystem = null,
        ImageFactoryInterface $imageFactory = null,
        LoggerInterface $logger = null
    ) {
        $this->filesystem = $filesystem ?: Factory::create();
        $imageFactory = $imageFactory ?: Discovery::find();
        $this->imageManager = new Manager($this->filesystem, $imageFactory);
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param string $type
     * @return HandlerInterface
     */
    public function getHandler($type)
    {
        $handler = $this->findHandler($type);
        if (!$handler) {
            throw new BadMethodCallException("Unknown projection type $type");
        }
        return $handler;
    }

    /**
     * @param string $type
     * @return HandlerInterface|null
     */
    public function findHandler($type)
    {
        if (!is_string($type)) {
            throw new BadMethodCallException('Non-string type provided');
        }
        if (isset($this->registry[$type])) {
            return $this->registry[$type];
        }
        $type = self::normalizeType($type);
        foreach (array_keys($this->registry) as $key) {
            $normalized = self::normalizeType($key);
            if (strpos($normalized, $type) === 0) {
                return $this->registry[$key];
            }
        }
        return null;
    }

    /**
     * Checks if handler exists.
     *
     * @param string $type
     * @return bool
     */
    public function exists($type)
    {
        return $this->findHandler($type) !== null;
    }

    /**
     * Finds and returns correct type name by passed string or just null.
     *
     * @param string|$type
     * @return string|null
     */
    public function findType($type)
    {
        $type = self::normalizeType($type);
        foreach (array_keys($this->registry) as $key) {
            $normalized = self::normalizeType($key);
            if (strpos($normalized, $type) === 0) {
                return $key;
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    public function register($name, HandlerInterface $handler)
    {
        $this->registry[$name] = $handler;
        return $handler;
    }

    /**
     * @return string[]
     */
    public function getRegisteredTypes()
    {
        return array_keys($this->registry);
    }

    /**
     * Registers bundled in types.
     */
    public function registerDefaultTypes()
    {
        $this->register(
            EquirectHandler::TYPE,
            new EquirectHandler($this->filesystem, $this->imageManager)
        );
        $this->register(
            CubeMapHandler::TYPE,
            new CubeMapHandler($this->filesystem, $this->imageManager)
        );
        return $this;
    }

    /**
     * Normalizes type name, removing non-word characters and casting
     * to lower case.
     *
     * @param string $type
     * @return string
     */
    public static function normalizeType($type)
    {
        return preg_replace('/\W/', '', strtolower($type));
    }
}
