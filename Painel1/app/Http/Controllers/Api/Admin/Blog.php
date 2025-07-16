<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Post;
use Core\Utils\Upload;

class Blog extends Api
{
    public function create()
    {
        try {
            $data = $this->request->post(false);

            $title = $data['title'];
            $content = $data['content'];
            $data = filter_var_array($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $post = new Post();
            $post->author = $data["author"];
            $post->category = $data["category"];
            $post->title = $title;
            $post->uri = str_slug($post->title);
            $post->subtitle = $data["subtitle"];
            $post->content = htmlspecialchars($content);
            $post->video = $data["video"];
            $post->status = $data["status"];
            $post->post_at = date_fmt_back($data["post_at"]);

            if (!empty($_FILES["cover"])) {
                $files = $_FILES["cover"];

                for ($i = 0; $i < count($files['type']); $i++) {
                    foreach (array_keys($files) as $keys) {
                        $images[$i][$keys] = $files[$keys][$i];
                    }
                }

                $upload = new Upload();
                $image = $upload->image($images[0], $post->title, 'images/blog');

                if (!$image) {
                    return [
                      'state' => false,
                      'message' => $upload->message()->getText(),
                    ];
                }

                $post->cover = $image;
            }

            if (!$post->checkSave()) {
                return [
                  'state' => false,
                  'message' => 'Whoops! Ocorreu um erro ao criar esta postagem, tente novamente.',
                ];
            }

            return [
              'state' => true,
              'message' => 'Post publicado com sucesso...',
            ];
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
