<?php

namespace Core\Utils;

/**
 * Class Thumb
 *
 * @author Gabriel Amorim <gabriel.amorim7788@gmail.com>
 * @package Core\Utils
 */
class Thumb
{
    /** @var Cropper */
    private $cropper;

    /** @var string */
    private $uploads;

    /**
     * Thumb constructor.
     */
    public function __construct()
    {
        $this->cropper = new Cropper(__DIR__ . '/../../storage/app/public/images/cache', 75, 5, true);
        $this->uploads = __DIR__ . '/../../storage/app/public/images';
    }

    /**
     * @param string $image
     * @param int $width
     * @param int|null $height
     * @return string
     */
    public function make(string $image, int $width, ?int $height = null): ?string
    {
        $make = $this->cropper->make("{$this->uploads}/{$image}", $width, $height);

        return (explode('../../storage/app/public/', $make))[1] ?? '';
    }

    /**
     * @param string|null $image
     */
    public function flush(?string $image = null): void
    {
        if ($image) {
            $this->cropper->flush("{$this->uploads}/{$image}");
            return;
        }

        $this->cropper->flush();
        return;
    }

    /**
     * @return Cropper
     */
    public function cropper(): Cropper
    {
        return $this->cropper;
    }
}
