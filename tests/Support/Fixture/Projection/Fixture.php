<?php

namespace AmaTeam\Image\Projection\Test\Support\Fixture\Projection;

use AmaTeam\Image\Projection\Filesystem\Pattern;
use AmaTeam\Image\Projection\Geometry\Box;

class Fixture
{
    const DEFINITIONS_FILE = 'Projections.yml';
    const INSTALLATION_PATH = 'External/Projection';
    const SOURCE_PATH_SUFFIX = '/source.jpg';
    const PATTERN_SUFFIX = '/faces/{face}/{x}/{y}.jpg';

    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $type;
    /**
     * @var Box
     */
    private $size;
    /**
     * @var Box
     */
    private $layout;
    /**
     * @var Offset[]
     */
    private $faces;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $workspace;
    /**
     * @var Pattern
     */
    private $pattern;
    /**
     * @var string
     */
    private $reference;
    /**
     * @var string
     */
    private $source;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Fixture
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Box
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param Box $size
     * @return Fixture
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return Box
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param Box $layout
     * @return Fixture
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return Offset[]
     */
    public function getFaces()
    {
        return $this->faces;
    }

    /**
     * @param Offset[] $faces
     * @return Fixture
     */
    public function setFaces($faces)
    {
        $this->faces = $faces;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Fixture
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * @param string $workspace
     * @return Fixture
     */
    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return Fixture
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return Pattern
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param Pattern $pattern
     */
    public function setPattern(Pattern $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
}
