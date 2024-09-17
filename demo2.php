<?php
require 'vendor/autoload.php';

use Esker\Common\Constant;
use Esker\Common\Debug;
use Esker\Esker;

$eskerServiceUsername = getenv('ESKER_USERNAME');
$eskerServicePassword = getenv('ESKER_PASSWORD');

try {
    $esker = new Esker($eskerServiceUsername, $eskerServicePassword, true);
    $results = $esker->getLetterStatuses('MOD.640091706750975884');

    if (count($results->transports) > 0) {
        $transport = $results->transports[0];
        $need_validate = false;
        $error_message = '';
        $error_code = 0;
        $validation_state = Constant::VALIDATION_STATES['FR'][1];
        echo 'Statut : ' . Constant::STATES['FR'][$transport->state] . '<br>';
        foreach ($transport->vars as $var) {
            switch ($var->attribute) {
                case 'ValidationState':
                    $validation_state = Constant::VALIDATION_STATES['FR'][$var->simpleValue];
                    break;
                case 'ShortStatus':
                    $error_message = $var->simpleValue;
                    break;
                case 'StatusCode':
                    $error_code = $var->simpleValue;
                    break;
                case 'NeedValidation':
                    $need_validate = $var->simpleValue;
                    break;
            }
        }
        echo 'Doit etre validé : ' . $need_validate . '<br>';
        echo 'Etat validation : ' . $validation_state . '<br>';
        echo 'Code erreur : ' . $error_code . '<br>';
        echo 'Message erreur : ' . $error_message . '<br>';
    } else {
        echo 'Rien !';
    }


    Debug::dump($results);
} catch (Exception $e) {
    Debug::dump($e);
    die();
}
/**
 * CompletionDateTime
 * State => Constant::STATES
 * ShortStatus -> string (message erreur)
 * StatusCode -> 0 si pas erreur
 *
 * if(NeedValidation===1 && ValidationState === 0){
 *      return 'Attente validation"
 * }
 * if(StatusCode !== 0){
 *      return "ERREUR: {{ShortStatus}}"
 * } else {
 *      return State
 * }
 *
 *
 * http://doc.esker.com/eskerondemand/cv_ly/fr/webservices/StartPage.htm#References/Fields/modeskerprintable.html#_ReceiptHasBeenReceived_
 * Voir si on fait recommandé en 100% numerique
 * ReceiptManageByFlyDoc -> Y (100% numerique)
 * ReceiptHasBeenReceived -> tracking si 100% numérique
 */
