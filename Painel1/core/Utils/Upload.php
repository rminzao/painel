<?php

namespace Core\Utils;

use Core\Uploader\File;
use Core\Uploader\Image;
use Core\Uploader\Media;

/**
 * FSPHP | Class Upload
 *
 * @author Gabriel Amorim <https://github.com/amorim778>
 * @package Core\Utils
 */
class Upload
{
    /** @var Message */
    private $message;

    /**
     * Upload constructor.
     */
    public function __construct()
    {
        $this->message = new Message();
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }

    /**
     * @param array $image
     * @param string $name
     * @param int $width
     * @return null|string
     * @throws \Exception
     */
    public function image(array $image, string $name, string $folder = "", int $width = 2000): ?string
    {
        $upload = new Image(__DIR__ . '/../../storage/app', 'public/' . $folder);
        if (empty($image['type']) || !in_array($image['type'], $upload::isAllowed())) {
            $this->message->error("Você não selecionou uma imagem válida");
            return null;
        }

        return str_replace(__DIR__ . '/../../storage/app/', "", $upload->upload($image, $name, $width));
    }

    /**
     * @param array $file
     * @param string $name
     * @return null|string
     * @throws \Exception
     */
    public function file(array $file, string $name): ?string
    {
        $upload = new File(__DIR__ . '/../../storage/app', 'public');
        if (empty($file['type']) || !in_array($file['type'], $upload::isAllowed())) {
            $this->message->error("Você não selecionou um arquivo válido");
            return null;
        }

        return str_replace(__DIR__ . '/../../storage/app' . "/", "", $upload->upload($file, $name));
    }

    /**
     * @param array $media
     * @param string $name
     * @return null|string
     * @throws \Exception
     */
    public function media(array $media, string $name): ?string
    {
        $upload = new Media(__DIR__ . '/../../storage/app', 'public');
        if (empty($media['type']) || !in_array($media['type'], $upload::isAllowed())) {
            $this->message->error("Você não selecionou uma mídia válida");
            return null;
        }

        return str_replace(__DIR__ . '/../../storage/app' . "/", "", $upload->upload($media, $name));
    }

    /**
     * @param string $filePath
     */
    public function remove(string $filePath): void
    {
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }
    }
}
