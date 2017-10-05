<?php

namespace AmaTeam\Image\Projection\Filesystem\Pattern;

/**
 * TODO: implement proper tokenization/parsing mechanism
 */
class Chunk
{
    const TYPE_EXACT_MATCH = 'exact';
    const TYPE_EXPRESSION = 'match';
    const PATTERN = '/\{([\w\-]+)\}/';

    /**
     * Chunk type, one of Chunk::TYPE_* constants
     *
     * @var string
     */
    private $type;
    /**
     * Original chunk text value
     *
     * @var string
     */
    private $source;
    /**
     * Regular expression built to match the chunk
     *
     * @var string
     */
    private $expression;
    /**
     * List of parameters specified in chunk
     *
     * @var string[]
     */
    private $parameters = [];

    public function __construct($chunk)
    {
        $this->source = $chunk;
        $this->expression = $chunk;
        $this->type = self::TYPE_EXACT_MATCH;
        $expression = preg_replace_callback(self::PATTERN, function ($match) {
            $parameter = $match[1];
            if (in_array($parameter, $this->parameters)) {
                return '(?:[\w\-]+)';
            }
            $this->parameters[] = $parameter;
            return sprintf('(?<%s>[\w\-]+)', $parameter);
        }, $chunk);
        if ($expression === $chunk) {
            return;
        }
        $this->type = self::TYPE_EXPRESSION;
        $this->expression = '/^' . $expression . '$/';
    }

    /**
     * @param string $segment
     * @return bool
     */
    public function matches($segment)
    {
        if ($this->type === self::TYPE_EXACT_MATCH) {
            return $segment === $this->expression;
        }
        return !!preg_match($this->expression, $segment);
    }

    /**
     * @param string $segment
     * @return string[]
     */
    public function getParameters($segment)
    {
        if ($this->type === self::TYPE_EXACT_MATCH) {
            return [];
        }
        if (!preg_match($this->expression, $segment, $matches)) {
            return [];
        }
        $target = [];
        foreach ($this->parameters as $parameter) {
            $target[$parameter] = $matches[$parameter];
        }
        return $target;
    }

    /**
     * @param array $parameters
     * @return Chunk
     */
    public function resolve(array $parameters)
    {
        $chunk = $this->source;
        foreach ($parameters as $key => $value) {
            $needle = "{{$key}}";
            if (strpos($chunk, $needle) !== false) {
                $chunk = str_replace($needle, $value, $chunk);
            }
        }
        return new Chunk($chunk);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->source;
    }
}
