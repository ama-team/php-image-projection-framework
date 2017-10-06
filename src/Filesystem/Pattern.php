<?php

namespace AmaTeam\Image\Projection\Filesystem;

use AmaTeam\Image\Projection\Filesystem\Pattern\Chunk;

class Pattern
{
    /**
     * @var string
     */
    private $source;
    /**
     * @var Chunk[]
     */
    private $chunks;
    /**
     * @var int
     */
    private $length;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->source = $pattern;
        $segments = self::split($pattern);
        $this->chunks = array_map([__CLASS__, 'createChunk'], $segments);
        $this->length = sizeof($segments);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function matches($path)
    {
        $segments = self::split($path);
        if (sizeof($segments) !== sizeof($this->chunks)) {
            return false;
        }
        for ($i = 0; $i < $this->length; $i++) {
            if (!$this->chunks[$i]->matches($segments[$i])) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $path
     * @return string[]
     */
    public function getParameters($path)
    {
        $segments = self::split($path);
        if (sizeof($segments) !== $this->length) {
            return [];
        }
        $parameters = [];
        for ($i = 0; $i < $this->length; $i++) {
            $parameters = array_merge(
                $parameters,
                $this->chunks[$i]->getParameters($segments[$i])
            );
        }
        return $parameters;
    }

    public function getFixedPart()
    {
        $candidates = $this->chunks;
        $accumulator = [];
        while (sizeof($candidates) > 0) {
            /** @var Chunk $chunk */
            $chunk = array_shift($candidates);
            if ($chunk->getType() !== Chunk::TYPE_EXACT_MATCH) {
                break;
            }
            $accumulator[] = $chunk->getExpression();
        }
        return implode('/', $accumulator);
    }

    public function isFixed()
    {
        foreach ($this->chunks as $chunk) {
            if ($chunk->getType() === Chunk::TYPE_EXPRESSION) {
                return false;
            }
        }
        return true;
    }

    public function resolve(array $parameters)
    {
        $resolved = array_map(function (Chunk $chunk) use ($parameters) {
            return (string) $chunk->resolve($parameters);
        }, $this->chunks);
        return new Pattern(implode('/', $resolved));
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    private static function split($path)
    {
        return array_filter(explode('/', trim($path, '/')), function ($item) {
            return $item !== '';
        });
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private static function createChunk($segment)
    {
        return new Chunk($segment);
    }
}
