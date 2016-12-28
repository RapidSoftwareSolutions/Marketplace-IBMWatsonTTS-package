<?php

$app->post('/api/IBMWatsonTTS/synthesizesTextToAudio', function ($request, $response, $args) {
    
    $settings = $this->settings;
    
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['username','password','text']);
    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }
    
    $auth = [$post_data['args']['username'], $post_data['args']['password']];
    
    $query_str = $settings['api_url'] . '/v1/synthesize';
    
    $headers['Content-Type'] = 'application/json';
    if(!empty($post_data['args']['accept'])) {
        $headers['Accept'] = $post_data['args']['accept'];
    }
    
    $body['text'] = $post_data['args']['text'];
    
    $query = [];
    if(!empty($post_data['args']['voice'])) {
        $query['voice'] = $post_data['args']['voice'];
    }
    if(!empty($post_data['args']['customizationId'])) {
        $query['customization_id'] = $post_data['args']['customizationId'];
    }
    
    $client = $this->httpClient;
    
    try {

        $resp = $client->post( $query_str, 
            [
                'auth' => $auth,
                'query' => $query,
                'headers' => $headers,
                'json' => $body,
                'stream' => true
            ]
        );
        
        $responseBody = $resp->getBody()->getContents();
  
        if(in_array($resp->getStatusCode(), ['200', '201', '202', '203', '204'])) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = base64_encode($responseBody);
            if(empty($result['contextWrites']['to'])) {
                $result['contextWrites']['to']['status_msg'] = "Api return no results";
            }
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();        
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ConnectException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';

    }
    
    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    
});
