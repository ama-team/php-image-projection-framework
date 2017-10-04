<?php

namespace AmaTeam\Image\Projection\Type\CubeMap\Mapping;

class Face
{
    const UP = 'u';
    const LEFT = 'l';
    const FRONT = 'f';
    const RIGHT = 'r';
    const BACK = 'b';
    const DOWN = 'd';
    /**
     * z = 1
     */
    const UP_DEFINITION = [
        'name' => self::UP,
        'pin' => [2, 1],
        'u' => [1, 1],
        'v' => [0, 1]
    ];
    /**
     * y = -1
     */
    const LEFT_DEFINITION = [
        'name' => self::LEFT,
        'pin' => [1, -1],
        'u' => [0, 1],
        'v' => [2, -1],
    ];
    /**
     * x = +1
     */
    const FRONT_DEFINITION = [
        'name' => self::FRONT,
        'pin' => [0, 1],
        'u' => [1, 1],
        'v' => [2, -1],
    ];
    /**
     * y = +1
     */
    const RIGHT_DEFINITION = [
        'name' => self::RIGHT,
        'pin' => [1, 1],
        'u' => [0, -1],
        'v' => [2, -1]
    ];
    /**
     * x = -1
     */
    const BACK_DEFINITION = [
        'name' => self::BACK,
        'pin' => [0, -1],
        'u' => [1, -1],
        'v' => [2, -1]
    ];
    /**
     * z = -1
     */
    const DOWN_DEFINITION = [
        'name' => self::DOWN,
        'pin' => [2, -1],
        'u' => [1, 1],
        'v' => [0, -1]
    ];

    /**
     * @var string
     */
    private $name;
    /**
     * @var int[]
     */
    private $pin;
    /**
     * @var int[]
     */
    private $uMapping;
    /**
     * @var int[]
     */
    private $vMapping;
    /**
     * @var int
     */
    private $size;
    /**
     * @var float|int
     */
    private $halfSize;

    /**
     * @param string $name
     * @param int $size
     * @param int[] $pin Ordinal number and value of dimension  being pinned
     * @param int[] $uMapping Ordinal number and multiplier of U dimension
     * @param int[] $vMapping Ordinal number and multiplier of V dimension
     */
    public function __construct(
        $name,
        $size,
        array $pin,
        array $uMapping,
        array $vMapping
    ) {
        $this->name = $name;
        $this->size = $size;
        $this->halfSize = $size / 2;
        $this->pin = $pin;
        $this->uMapping = $uMapping;
        $this->vMapping = $vMapping;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public static function getNames()
    {
        return [
            self::FRONT,
            self::BACK,
            self::RIGHT,
            self::LEFT,
            self::UP,
            self::DOWN
        ];
    }

    /**
     * Maps vector to UV coordinates in [0..$size, 0..$size] range
     *
     * @param float[] $vector
     * @return float[]
     */
    public function map(array $vector)
    {
        $u = $this->halfSize + ($vector[$this->uMapping[0]] * $this->uMapping[1]);
        $v = $this->halfSize + ($vector[$this->vMapping[0]] * $this->vMapping[1]);
        return [$u, $v];
    }

    /**
     * Maps UV coordinates to vector in [-$size/2..$size/2 (x), (y), (z)] range
     *
     * @param int $u
     * @param int $v
     * @return float[]
     */
    public function vectorize($u, $v)
    {
        $target = [];
        $target[$this->uMapping[0]] = ($u - $this->halfSize) * $this->uMapping[1];
        $target[$this->vMapping[0]] = ($v - $this->halfSize) * $this->vMapping[1];
        $target[$this->pin[0]] = $this->pin[1] * $this->halfSize;
        // intentional unrolling
        $squared = $target[0] * $target[0] + $target[1] * $target[1] +
            $target[2] * $target[2];
        $target[3] = sqrt($squared);
        return $target;
    }

    public static function create($definition, $size)
    {
        return new Face(
            $definition['name'],
            $size,
            $definition['pin'],
            $definition['u'],
            $definition['v']
        );
    }

    /**
     * @param int $size
     * @return Face[]
     */
    public static function generateCubeFaces($size)
    {
        return [
            self::create(self::FRONT_DEFINITION, $size),
            self::create(self::BACK_DEFINITION, $size),
            self::create(self::RIGHT_DEFINITION, $size),
            self::create(self::LEFT_DEFINITION, $size),
            self::create(self::UP_DEFINITION, $size),
            self::create(self::DOWN_DEFINITION, $size)
        ];
    }
}
