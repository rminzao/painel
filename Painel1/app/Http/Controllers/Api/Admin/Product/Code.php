<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Http\Controllers\Api\Api;
use App\Models\Invoice;
use App\Models\ProductCode;
use App\Models\User;
use Core\Routing\Request;
use Core\View\Paginator;

class Code extends Api
{
    public function list(Request $request)
    {
        $get = $request->get();

        $sid    = $get['sid'] ?? 0;
        $page   = $get['page'] ?? 1;
        $limit  = $get['limit'] ?? 10;
        $search = $get['search'] ?? '';

        $query = (new ProductCode())->select('*');

        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('id', $search) :
                $query->where('code', 'LIKE', "%{$search}%");
        }

        if ($sid != 0) {
            $query = $query->where('sid', $sid);
        }

        $query = $query->orderBy('expires_at', 'DESC');

        $pager = new Paginator(url($request->getUri()), onclick: 'pCode.list');
        $pager->pager($query->count(), $limit, $page, 2);

        $data = $query
            ->limit($pager->limit())
            ->offset($pager->offset())->get()?->toArray();

        foreach ($data as &$item) {
            $item['status'] = $this->getEventStateByDate($item['start_at'], $item['expires_at']);
            $item['use_list'] = $this->getUseList($item['code']);
        }

        return [
            'state'     => true,
            'data'      => $data,
            'paginator' => [
                'total'    => $pager->pages(),
                'current'  => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function create(Request $request)
    {
        $post = $request->post(false);
        $check = $post;

        unset($check['param2']);

        if (in_array('', $check)) {
            return [
                'state' => false,
                'message' => 'Preencha todos os campos.'
            ];
        }

        $model = new ProductCode();

        $check = $model->where('code', $post['code'])->first();
        if ($check) {
            return [
                'state' => false,
                'message' => "O cupom com nome <span class=\"text-primary\">{$check->code}</span> já existe."
            ];
        }

        $post['state'] = isset($post['state']) ? 1 : 0;
        $post['repeat'] = isset($post['repeat']) ? 1 : 0;

        if (!$model->insert($post)) {
            return [
                'state' => true,
                'message' => 'Falha ao criar cupom promocional.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Cupom promocional criado com sucesso.'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);

        $id = $post['id'] ?? 0;

        $pCode = ProductCode::find($id);
        if (!$pCode) {
            return [
                'state' => false,
                'message' => 'O código promocional não foi encontrado.'
            ];
        }

        unset($post['id']);
        unset($post['code']);

        $post['state'] = isset($post['state']) ? 1 : 0;
        $post['repeat'] = isset($post['repeat']) ? 1 : 0;

        if (!$pCode->update($post)) {
            return [
                'state' => false,
                'message' => 'Ocorreu um erro ao atualizar o código promocional,'
            ];
        }

        return [
            'state' => true,
            'message'  => 'Código promocional atualizado com sucesso.'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();

        $id = $post['id'] ?? 0;

        $pCode = ProductCode::find($id);
        if (!$pCode) {
            return [
                'state' => false,
                'message' => 'O código promocional não foi encontrado.'
            ];
        }

        if (!$pCode->delete()) {
            return [
                'state' => false,
                'message' => 'Ocorreu um erro ao deletar o código promocional,'
            ];
        }

        return [
            'state' => true,
            'message'  => 'Código promocional foi deletado com sucesso.'
        ];
    }

    protected function getUseList(string $code): array
    {
        $query = Invoice::where('code', $code);

        $data = $query->get()?->toArray() ?? [];

        foreach ($data as &$item) {
            $user = User::find($item['uid']);
            if (!$user) {
                continue;
            }

            $user['avatar'] = image_avatar($user->photo, 50, 50);
            $item['value'] = str_price($item['value']);
            $item['user'] = $user->toArray();
        }

        return $data;
    }

    protected function getEventStateByDate($beginTime, $endTime)
    {
        $now = time();
        if ($now < strtotime($beginTime)) {
            return 'before';
        }
        if ($now > strtotime($endTime)) {
            return 'after';
        }
        return 'active';
    }
}
