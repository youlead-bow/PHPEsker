<?php

namespace Esker\Common;

/**
 * Class Constant
 * @package Esker\Common
 */
class Constant
{
    public const array WSFILE_MODE = [
        'MODE_UNDEFINED' => 'MODE_UNDEFINED',
        'MODE_ON_SERVER' => 'MODE_ON_SERVER',
        'MODE_INLINED' => 'MODE_INLINED'
    ];

    public const array RESOURCE_TYPE = [
        'TYPE_STYLESHEET' => 'TYPE_STYLESHEET',
        'TYPE_IMAGE' => 'TYPE_IMAGE',
        'TYPE_COVER' => 'TYPE_COVER'
    ];

    public const array ATTACHMENTS_FILTER = [
        'FILTER_NONE' => 'FILTER_NONE',
        'FILTER_ALL' => 'FILTER_ALL',
        'FILTER_CONVERTED' => 'FILTER_CONVERTED',
        'FILTER_SOURCE' => 'FILTER_SOURCE'
    ];

    public const array STATES = [
        'FR' => [
            0 => 'En cours de traitement - Mis en file d\'attente',
            10 => 'En cours de traitement - Soumis',
            30 => 'En cours de traitement - Accepté',
            40 => 'En cours de traitement - Conversion en cours',
            50 => 'En cours de traitement - Prêt',
            60 => 'En cours de traitement - Nouvelle tentative',
            70 => 'A valider',
            80 => 'Occupé',
            90 => 'En cours de traitement - Veuillez patienter',
            100 => 'Réussi',
            200 => 'Echec',
            300 => 'Annulé',
            400 => 'Rejeté'
        ],
        'EN' => [
            0 => 'Being processed - Queued',
            10 => 'Being processed - Submitted',
            30 => 'Being processed - Accepted',
            40 => 'Being processed - Converting',
            50 => 'Being processed - Ready',
            60 => 'Being processed - On retry',
            70 => 'To validate',
            80 => 'Busy',
            90 => 'Being processed - Waiting',
            100 => 'Successful',
            200 => 'Failure',
            300 => 'Canceled',
            400 => 'Rejected'
        ]
    ];

    public const array VALIDATION_STATES = [
        'FR' => [
            0 => 'Non traité',
            1 => 'Approuvé',
            2 => 'Rejeté',
            3 => 'Transféré',
            4 => 'Transféré par l\'administrateur',
            5 => 'Rerouté par le gestionnaire',
            6 => 'Le document à été divisé et attend que les documents émis soient validés'
        ],
        'EN' => [
            0 => 'Unprocessed',
            1 => 'Approved',
            2 => 'Rejected',
            3 => 'Forwarded (User forms only)',
            4 => 'Transferred by the administrator (User forms only)',
            5 => 'Rerouted by the Out of Office Manager (User forms only)',
            6 => 'Document has been split and is waiting for the issued documents to be validated. (User forms only)'
        ]
    ];

    public const array MAIL_TYPE = [
        'FR' => [
            'Prioritaire' => 'U', // default
            'Economique' => 'E',
            'Recommande' => 'R1',
            'Ecopli' => 'En'
        ],
        'US' => [
            'FirstClass' => 'F' // default
        ],
        'ES' => [
            'Standard' => 'S' // default
        ],
        'UK' => [
            'Premier' => 'P', // default
            'Standard' => 'S'
        ],
        'SP' => [
            'Standard' => 'S' // default
        ],
        'BE' => [
            'Prior' => 'P', // default
            'PriorWithMailID' => 'PMID',
            'NonPrior' => 'NP',
            'DM1' => 'DM1',
            'DM2' => 'DM2',
            'Registered' => 'R'
        ]
    ];
}
