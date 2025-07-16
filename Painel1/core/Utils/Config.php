<?php

namespace Core\Utils;

class Config 
{
  /** @var string */
  protected $path;
  
  /** @var array */
  protected $config = [];

  public function __construct() {
    $this->path = __DIR__ . '/../../config/';
    $this->init();
  }

  protected function init()
  {
    $files = array_diff(scandir($this->path), ['.', '..']);

    foreach($files as $file){
      $name = (explode('.', $file))[0];
      $content = require $this->path . $file;
      $this->config[$name] = $content;
    }
  }

  public function get(string $key)
  {
    $key = explode('.', $key);
    $root = $key[0];
    $child = $key[1] ?? null;

    return $this->config[$root][$child] ?? $this->config[$root];
  }
}