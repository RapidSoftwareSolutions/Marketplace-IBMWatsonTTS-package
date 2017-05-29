<?php

$app->post('/api/IBMWatsonTTS/synthesizesTextToAudio', function ($request, $response, $args) {

    $settings = $this->settings;

    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['username', 'password', 'text']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $auth = [$post_data['args']['username'], $post_data['args']['password']];

    $query_str = $settings['api_url'] . '/v1/synthesize';

    $headers['Content-Type'] = 'application/json';
    if (!empty($post_data['args']['accept'])) {
        $headers['Accept'] = $post_data['args']['accept'];
    }

    $body['text'] = $post_data['args']['text'];

    $query = [];
    if (!empty($post_data['args']['voice'])) {
        $query['voice'] = $post_data['args']['voice'];
    }
    if (!empty($post_data['args']['customizationId'])) {
        $query['customization_id'] = $post_data['args']['customizationId'];
    }

    $client = $this->httpClient;

    try {
        $resp = $client->post($query_str, [
            'headers' => $headers,
            "auth" => $auth,
            "json" => $body,
            "stream" => 'true'
        ]);
        $responseBody = $resp->getBody()->getContents();

        if ($resp->getStatusCode() == 200) {
            $size = $resp->getHeader('Content-Length')[0];
            $contentDisposition = $resp->getHeader('Content-Disposition')[0];
            $contentDisposition = str_replace("attachment", "", $contentDisposition);
            $contentDisposition = str_replace('filename=', "", $contentDisposition);
            $contentDisposition = str_replace(';', "", $contentDisposition);
            $uploadServiceResponse = $client->post($settings['uploadServiceUrl'], [
                'multipart' => [
                    [
                        "name" => "file",
                        "filename" => trim($contentDisposition),
                        "contents" => $responseBody
                    ],
                    [
                        'name' => 'length',
                        'contents' => $size
                    ]
                ]
            ]);
            $uploadServiceResponseBody = $uploadServiceResponse->getBody()->getContents();
            if ($uploadServiceResponse->getStatusCode() == 200) {
                $result['callback'] = 'success';
                $result['contextWrites']['to'] = json_decode($uploadServiceResponse->getBody());
            }
            else {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                $result['contextWrites']['to']['status_msg'] = is_array($uploadServiceResponseBody) ? $uploadServiceResponseBody : json_decode($uploadServiceResponseBody);
            }
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }
    } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = json_decode($exception->getResponse()->getBody());
    }
    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});