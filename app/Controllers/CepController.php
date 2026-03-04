<?php

class CepController
{
    public function buscar()
    {
        header("Content-Type: application/json; charset=UTF-8");

        $cep = $_GET['cep'] ?? null;

        if (!$cep) {
            http_response_code(400);
            echo json_encode(["erro" => true, "msg" => "CEP não informado"]);
            return;
        }

        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            http_response_code(400);
            echo json_encode(["erro" => true, "msg" => "CEP inválido"]);
            return;
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5
        ]);

        $response = curl_exec($ch);

        if (!$response) {
            http_response_code(500);
            echo json_encode(["erro" => true]);
            return;
        }

        echo $response;
    }
}