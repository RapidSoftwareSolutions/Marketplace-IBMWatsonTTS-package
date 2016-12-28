<?php
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
    'metadata'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}

