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

    $result = [];

    $client->postAsync($query_str,
        [
            'auth' => $auth,
            'query' => $query,
            'headers' => $headers,
            'json' => $body,
            'stream' => true
        ]
    )
        ->then(
            function (\Psr\Http\Message\ResponseInterface $response) use ($client, $post_data, $settings, &$result) {
                $responseApi = $response->getBody()->getContents();

                $size = strlen($responseApi);

                if (in_array($response->getStatusCode(), ['200', '201', '202', '203', '204'])) {
                    try {
                        $fileUrl = $client->post($settings['uploadServiceUrl'], [
                            'multipart' => [
                                [
                                    'name' => 'length',
                                    'contents' => $size
                                ],
                                [
                                    'name' => 'file',
                                    'filename' => bin2hex(random_bytes(5)) . $settings['fileExtensions']['wav'],
                                    'contents' => $responseApi
                                ],
                            ]
                        ]);

                        $gcloud = $fileUrl->getBody()->getContents();

                        $resultDecoded = json_decode($gcloud, true);

                        $result['callback'] = 'success';
                        $result['contextWrites']['to'] = ($resultDecoded != NULL) ? $resultDecoded : $gcloud;
                    } catch (GuzzleHttp\Exception\BadResponseException $exception) {
                        $result['callback'] = 'error';
                        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
                        $result['contextWrites']['to']['status_msg'] = 'Something went wrong during file link receiving.';
                    }

                } else {
                    $resultDecoded = json_decode($responseApi, true);

                    $result['callback'] = 'error';
                    $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                    $result['contextWrites']['to']['status_msg'] = ($resultDecoded != NULL) ? $resultDecoded : $responseApi;

                }
            },
            function (GuzzleHttp\Exception\BadResponseException $exception) use (&$result) {

                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                $result['contextWrites']['to']['status_msg'] = $exception->getMessage();

            },
            function (GuzzleHttp\Exception\ConnectException $exception) use (&$result) {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
                $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';

            }
        )
        ->wait();

    return $response->withHeader('Content-type', 'application/json')->withJson($result, 200, JSON_UNESCAPED_SLASHES);

});
