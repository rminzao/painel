<?php

namespace Core\Utils;

class PicPay
{
    private $token;

    private $sellerToken;

    public function __construct(string $token, string $sellerToken)
    {
        $this->token = $token;
        $this->sellerToken = $sellerToken;
    }

    public function request($produto, $cliente)
    {
        $data = [
            'referenceId' => $produto->ref,
            'callbackUrl' => $produto->urlCallBack,
            'returnUrl'   => $produto->urlReturn,
            'value'       => $produto->valor,
            'buyer'       => [
                'firstName' => $cliente->nome,
                'lastName'  => $cliente->sobreNome,
                'document'  => $cliente->cpf,
                'email'     => $cliente->email,
                'phone'     => $cliente->telefone
            ]
        ];

        $ch = curl_init('https://appws.picpay.com/ecommerce/public/payments');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-picpay-token: ' . $this->token]);

        $res = curl_exec($ch);
        curl_close($ch);

        $return = json_decode($res);

        return $return;
    }

    public function notification()
    {
        $content = trim(file_get_contents("php://input"));
        $payBody = json_decode($content);

        if (!isset($payBody->authorizationId)) {
            return false;
        }

        $referenceId = $payBody->referenceId;

        $ch = curl_init('https://appws.picpay.com/ecommerce/public/payments/' . $referenceId . '/status');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-picpay-token: ' . $this->token));

        $res = curl_exec($ch);
        curl_close($ch);
        $notification = json_decode($res);

        $notification->referenceId     = $payBody?->referenceId ?? 0;
        $notification->authorizationId = $payBody?->authorizationId ?? 0;

        return $notification;
    }

    public function getStatus($referenceId)
    {
        $ch = curl_init('https://appws.picpay.com/ecommerce/public/payments/' . $referenceId . '/status');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-picpay-token: ' . $this->token));

        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res);
    }
}
