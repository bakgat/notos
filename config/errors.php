<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 13:51
 */
return [

    /*
    |—————————————————————————————————————
    | Default Errors
    |—————————————————————————————————————
    */

    'bad_request' => [
        'title' => 'De server kan of wil het verzoek niet verwerken als gevolg van iets dat als fout van de client wordt gezien.',
        'detail' => 'Er was een fout in uw verzoek. Probeer opnieuw.'
    ],

    'forbidden' => [
        'title' => 'Het verzoek was geldig, maar de server weigerde te antwoorden.',
        'detail' => 'Uw verzoek was geldig, maar u bent niet geautoriseerd om deze actie uit te voeren.'
    ],

    'not_found' => [
        'title' => 'De opgevraagde bron is niet gevonden, maar kan weer beschikbaar zijn in de toekomst. Latere verzoeken van de klant zijn toegestaan.',
        'detail' => 'De bron die u zoekt is niet gevonden.'
    ],

    'precondition_failed' => [
        'title' => 'De server voldoet niet aan een van de voorwaarden die werden aangevraagd.',
        'detail' => 'Uw aanvraag voldeed niet aan de vereiste voorwaarden.'
    ],

    'duplicate' => [
        'title' => 'Het verzoek was geldig, maar de server kon het niet verwerken als gevolg van semantische fouten.',
        'detail' => '%s [%s] bestaat al.'
    ],

    'unsupported_media_type' => [
        'title' => 'Het verzoek heeft een media-type dat de server niet ondersteunt.',
        'detail' => 'Uw verzoek met het media-type [%s] wordt niet ondersteund.'
    ],

    'org_not_found' => [
        'title' => 'De opgevraagde organisatie werd niet gevonden, maar kan weer beschikbaar zijn in de toekomst. Latere verzoeken van de klant zijn toegestaan.',
        'detail' => 'De organisatie [%s] is niet gevonden.'
    ],

    'user_not_found' => [
        'title' => 'De opgevraagde gebruiker werd niet gevonden, maar kan weer beschikbaar zijn in de toekomst. Latere verzoeken van de klant zijn toegestaan.',
        'detail' => 'De gebruiker [%s] is niet gevonden.'
    ],

];