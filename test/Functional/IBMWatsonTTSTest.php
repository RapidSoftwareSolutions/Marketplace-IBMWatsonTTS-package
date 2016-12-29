<?php

namespace Test\Functional;

require_once(__DIR__ . '/../../src/Models/checkRequest.php');

class IBMWatsonTTSTest extends BaseTestCase {
    
    public function testPackage() {
        
        $routes = [
            'getVoices',
            'getSingleVoice',
            'synthesizesTextToAudio',
            'getPronunciation',
            'createVoiceModel',
            'updateVoiceModel',
            'getVoiceModels',
            'getSingleVoiceModel',
            'addWordsToVoiceModel',
            'addSingleWordToVoiceModel',
            'getVoiceModelWords',
            'getVoiceModelSingleWord',
            'deleteVoiceModelSingleWord',
            'deleteVoiceModel',
        ];
        
        foreach($routes as $file) {
            $var = '{  
                        "args":{}
                    }';
            $post_data = json_decode($var, true);

            $response = $this->runApp('POST', '/api/IBMWatsonTTS/'.$file, $post_data);

            $this->assertEquals(200, $response->getStatusCode(), 'Error in '.$file.' method');
        }
    }
    
}
