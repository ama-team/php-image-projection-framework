<?php

namespace AmaTeam\Image\Projection\Type\CubeMap\Mapping;

/**
 * This class represents single face of a cube map and is used to
 * convert UV coordinates to vector emerging from cube center and vice
 * versa.
 *
 * Conventions used are common ones:
 * - UV coordinates are set in [0, 1] range
 * - Vector is a four-element array: [x, y, z, length]. xyz coordinates
 *   are set in [-1, 1] range, length depends on xyz values and lies in
 *   range 0..sqrt(3).
 * - X and Z axis are located in 'horizontal' plane, Y is located in
 *   'vertical' plane; X is directed towards R(ight) face, Y is
 *   directed towards U(p) face, Z is directed towards F(ront) face.
 *
 * @see https://docs.unity3d.com/uploads/Textures/CubeLayout6Faces.png
 * @see http://www.3dcpptutorials.sk/obrazky/cube_map.jpg
 * @see https://www.evl.uic.edu/aej/525/pics/cubemap-diagram.jpg
 */
class Face
{
    const UP = 'u';
    const LEFT = 'l';
    const FRONT = 'f';
    const RIGHT = 'r';
    const BACK = 'b';
    const DOWN = 'd';

    // storing as static variables because older HHVM doesn't allow to
    // store arrays in constants

    /**
     * y = +1
     */
    private static $upDefinition = [
        'name' => self::UP,
        'pin' => [1, 1],
        'u' => [0, 1],
        'v' => [2, 1]
    ];
    /**
     * x = -1
     */
    private static $leftDefinition = [
        'name' => self::LEFT,
        'pin' => [0, -1],
        'u' => [2, 1],
        'v' => [1, -1],
    ];
    /**
     * z = +1
     */
    private static $frontDefinition = [
        'name' => self::FRONT,
        'pin' => [2, 1],
        'u' => [0, 1],
        'v' => [1, -1],
    ];
    /**
     * x = +1
     */
    private static $rightDefinition = [
        'name' => self::RIGHT,
        'pin' => [0, 1],
        'u' => [2, -1],
        'v' => [1, -1]
    ];
    /**
     * z = -1
     */
    private static $backDefinition = [
        'name' => self::BACK,
        'pin' => [2, -1],
        'u' => [0, -1],
        'v' => [1, -1]
    ];
    /**
     * y = -1
     */
    private static $downDefinition = [
        'name' => self::DOWN,
        'pin' => [1, -1],
        'u' => [0, 1],
        'v' => [2, -1]
    ];

    public static function getUpDefinition()
    {
        /**
         * y = +1
         */
        return [
            'name' => self::UP,
            'pin' => [1, 1],
            'u' => [0, 1],
            'v' => [2, 1]
        ];
    }

    public static function getLeftDefinition()
    {
        /**
         * x = -1
         */
        return [
            'name' => self::LEFT,
            'pin' => [0, -1],
            'u' => [2, 1],
            'v' => [1, -1],
        ];
    }

    public static function getFrontDefinition()
    {

        /**
         * z = +1
         */
        return [
            'name' => self::FRONT,
            'pin' => [2, 1],
            'u' => [0, 1],
            'v' => [1, -1],
        ];
    }

    public static function getRightDefinition()
    {
        /**
         * x = +1
         */
        return [
            'name' => self::RIGHT,
            'pin' => [0, 1],
            'u' => [2, -1],
            'v' => [1, -1]
        ];
    }

    public static function getBackDefinition()
    {
        /**
         * z = -1
         */
        return [
            'name' => self::BACK,
            'pin' => [2, -1],
            'u' => [0, -1],
            'v' => [1, -1]
        ];
    }

    public static function getDownDefinition()
    {
        /**
         * y = -1
         */
        return [
            'name' => self::DOWN,
            'pin' => [1, -1],
            'u' => [0, 1],
            'v' => [2, -1]
        ];
    }

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
     * @param string $name
     * @param int[] $pin Ordinal number and value of dimension  being pinned
     * @param int[] $uMapping Ordinal number and multiplier of U dimension
     * @param int[] $vMapping Ordinal number and multiplier of V dimension
     */
    public function __construct(
        $name,
        array $pin,
        array $uMapping,
        array $vMapping
    ) {
        $this->name = $name;
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
            self::RIGHT,
            self::LEFT,
            self::UP,
            self::DOWN,
            self::FRONT,
            self::BACK,
        ];
    }

    /**
     * Maps vector to UV coordinates in [0..1, 0..1] range
     *
     * @param float[] $vector
     * @return float[]
     */
    public function map(array $vector)
    {
        $max = max(abs($vector[0]), abs($vector[1]), abs($vector[2]));
        $vector = Vector::multiply($vector, 1 / $max);
        $u = 0.5 + ($vector[$this->uMapping[0]] * $this->uMapping[1] / 2);
        $v = 0.5 + ($vector[$this->vMapping[0]] * $this->vMapping[1] / 2);
        return [$u, $v];
    }

    /**
     * Maps UV coordinates to vector in [-1..1 (x), (y), (z)] range
     *
     * @param float $u
     * @param float $v
     * @return float[]
     */
    public function vectorize($u, $v)
    {
        $target = [];
        $target[$this->uMapping[0]] = ($u - 0.5) * 2 * $this->uMapping[1];
        $target[$this->vMapping[0]] = ($v - 0.5) * 2 * $this->vMapping[1];
        $target[$this->pin[0]] = $this->pin[1];
        // intentional unrolling
        $squared = $target[0] * $target[0] + $target[1] * $target[1] +
            $target[2] * $target[2];
        $target[3] = sqrt($squared);
        return $target;
    }

    public static function create($definition)
    {
        return new Face(
            $definition['name'],
            $definition['pin'],
            $definition['u'],
            $definition['v']
        );
    }

    /**
     * @return Face[]
     */
    public static function generateCubeFaces()
    {
        return [
            self::create(self::$rightDefinition),
            self::create(self::$leftDefinition),
            self::create(self::$upDefinition),
            self::create(self::$downDefinition),
            self::create(self::$frontDefinition),
            self::create(self::$backDefinition),
        ];
    }
}
