# IBMWatsonTTS Package
The IBM® Text to Speech service provides an API that uses IBM's speech-synthesis capabilities to synthesize text into natural-sounding speech in a variety of languages, accents, and voices.
* Domain: ibm.com
* Credentials: username, password

## How to get credentials: 
0. Register to [IBM Bluemix Console](https://console.ng.bluemix.net/registration/) 
1. After log in choose Text to Speech from [services](https://console.ng.bluemix.net/catalog/?category=watson)
2. Connect Text to Speech to your application at the left side, choose pricing plan and click on 'Create' button at the bottom of the page.
3. Click on 'Service Credentials' tab to see your username and password.
 
## IBMWatsonTTS.getVoices
Retrieves a list of all voices available for use with the service. The information includes the voice's name, language, and gender, among other things.

| Field   | Type       | Description
|---------|------------|----------
| username| credentials| username obtained from IBM Bluemix.
| password| credentials| password obtained from IBM Bluemix.

## IBMWatsonTTS.getSingleVoice
Lists information about the specified voice.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| voice          | String     | The voice about which information is to be returned. Avaliable values: de-DE_BirgitVoice, de-DE_DieterVoice, en-GB_KateVoice, en-US_AllisonVoice, en-US_LisaVoice, en-US_MichaelVoice, es-ES_LauraVoice, es-ES_EnriqueVoice, es-LA_SofiaVoice, es-US_SofiaVoice, fr-FR_ReneeVoice, it-IT_FrancescaVoice, ja-JP_EmiVoice, pt-BR_IsabelaVoice
| customizationId| String     | The GUID of a custom voice model about which information is to be returned. You must make the request with the service credentials of the model's owner. Omit the parameter to see information about the voice with no customization.

## IBMWatsonTTS.synthesizesTextToAudio
Synthesizes text to spoken audio, returning the synthesized audio stream as an array of bytes.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| text           | String     | Provide plain text or text that is annotated with SSML. Text size is limited to 5 KB. 
| accept         | String     | The requested MIME type of the audio. Possible values: audio/ogg;codecs=opus (the default), audio/wav, audio/flac, audio/l16;rate=rate, audio/mulaw;rate=rate, audio/basic
| voice          | String     | The voice that is to be used for the synthesis. Avaliable values: de-DE_BirgitVoice, de-DE_DieterVoice, en-GB_KateVoice, en-US_AllisonVoice, en-US_LisaVoice, en-US_MichaelVoice, es-ES_LauraVoice, es-ES_EnriqueVoice, es-LA_SofiaVoice, es-US_SofiaVoice, fr-FR_ReneeVoice, it-IT_FrancescaVoice, ja-JP_EmiVoice, pt-BR_IsabelaVoice
| customizationId| String     | The GUID of a custom voice model about which information is to be returned. You must make the request with the service credentials of the model's owner. Omit the parameter to see information about the voice with no customization.

## IBMWatsonTTS.getPronunciation
Returns the phonetic pronunciation for the specified word.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| text           | String     | Provide plain text or text that is annotated with SSML. Text size is limited to 5 KB. 
| voice          | String     | The voice in which the pronunciation for the specified word is to be returned. Avaliable values: de-DE_BirgitVoice, de-DE_DieterVoice, en-GB_KateVoice, en-US_AllisonVoice, en-US_LisaVoice, en-US_MichaelVoice, es-ES_LauraVoice, es-ES_EnriqueVoice, es-LA_SofiaVoice, es-US_SofiaVoice, fr-FR_ReneeVoice, it-IT_FrancescaVoice, ja-JP_EmiVoice, pt-BR_IsabelaVoice
| format         | String     | The phoneme set in which the pronunciation is to be returned: ipa (the default), ibm
| customizationId| String     | The GUID of a custom voice model about which information is to be returned. You must make the request with the service credentials of the model's owner. Omit the parameter to see information about the voice with no customization.

## IBMWatsonTTS.createVoiceModel
Creates a new empty custom voice model that is owned by the requesting user.

| Field      | Type       | Description
|------------|------------|----------
| username   | credentials| username obtained from IBM Bluemix.
| password   | credentials| password obtained from IBM Bluemix.
| name       | String     | The name of the new custom voice model.
| language   | String     | The language of the new custom voice model. Avaliable values: de-DE, en-GB, en-US (the default), es-ES, es-LA, es-US, fr-FR, it-IT, ja-JP, pt-BR
| description| String     | A description of the new custom voice model.

## IBMWatsonTTS.getSingleVoiceModel
Lists all information about the specified custom voice model.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model that is to be returned. You must make the request with the service credentials of the model's owner.

## IBMWatsonTTS.updateVoiceModel
Updates information for the specified custom voice model.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model to be updated. You must make the request with the service credentials of the model's owner.
| name           | String     | A new name for the custom voice model.
| description    | String     | A description of the new custom voice model.
| words          | JSON       | Array of objects. An array of WordUpdate objects that provides a list of words and their translations to be added to or updated in the custom voice model. Send an empty array to make no additions or updates. See README for more details.

#### words format
```json

[
        {"word":"iPhone", "translation":"I phone"}
]
```
## IBMWatsonTTS.getVoiceModels
Lists metadata such as the name and description for all custom voice models that you own for all languages. Specify a language to list the voice models that you own for the specified language only.

| Field   | Type       | Description
|---------|------------|----------
| username| credentials| username obtained from IBM Bluemix.
| password| credentials| password obtained from IBM Bluemix.
| language| String     | The language for which custom voice models owned by the requester are to be returned. Possible values: de-DE, en-GB, en-US, es-ES, es-LA, es-US, fr-FR, it-IT, ja-JP, pt-BR

## IBMWatsonTTS.addWordsToVoiceModel
Adds one or more words and their translations to the specified custom voice model. Adding a new translation for a word that already exists in a custom model overwrites the word's existing translation.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model that is to be updated. You must make the request with the service credentials of the model's owner.
| words          | JSON       | Array of objects. An array of WordAdd objects that provides information about the words to be added to the custom voice model. See README for more details.

#### words format
```json

[  
    {  
        "word":"EEE",
        "translation":"<phoneme alphabet=\"ibm\" ph=\"tr1Ipxl.1i\"></phoneme>"
    },
    {  
        "word":"IEEE",
        "translation":"<phoneme alphabet=\"ibm\" ph=\"1Y.tr1Ipxl.1i\"></phoneme>"
    }
]
```
## IBMWatsonTTS.addSingleWordToVoiceModel
Adds a single word and its translation to the specified custom voice model. Adding a new translation for a word that already exists in a custom model overwrites the word's existing translation.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model that is to be updated. You must make the request with the service credentials of the model's owner.
| word           | String     | The word that is to be added to the custom voice model.
| translation    | String     | The phonetic or sounds-like translation for the word. A phonetic translation is based on the SSML format for representing the phonetic string of a word either as an IPA or IBM SPR translation. A sounds-like translation consists of one or more words that, when combined, sound like the word.

## IBMWatsonTTS.getVoiceModelWords
Lists all of the words and their translations for the specified custom voice model.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model that is to be updated. You must make the request with the service credentials of the model's owner.

## IBMWatsonTTS.getVoiceModelSingleWord
Returns the translation for a single word from the specified custom model.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model that is to be updated. You must make the request with the service credentials of the model's owner.
| word           | String     | The word that is to be returned from the custom voice model.

## IBMWatsonTTS.deleteVoiceModelSingleWord
Deletes a single word from the specified custom voice model. Only the owner of a custom voice model can use this method to delete a word from the model.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model from which the word is to be deleted. You must make the request with the service credentials of the model's owner.
| word           | String     | The word that is to be deleted from the custom voice model.

## IBMWatsonTTS.deleteVoiceModel
Deletes the custom voice model with the specified customization_id. Only the owner of a custom voice model can use this method to delete the model.

| Field          | Type       | Description
|----------------|------------|----------
| username       | credentials| username obtained from IBM Bluemix.
| password       | credentials| password obtained from IBM Bluemix.
| customizationId| String     | The GUID of the custom voice model that is to be deleted. You must make the request with the service credentials of the model's owner.

