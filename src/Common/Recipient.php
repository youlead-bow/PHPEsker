<?php

namespace Esker\Common;

/**
 * Class Recipient
 * @package Esker\Common
 */
class Recipient
{
    public const string AUDIT = 'AUD';
    public const string COPY_FILE = 'CF';
    public const string EMAIL = 'SM';
    public const string FORM = 'CD#<xxx>';
    public const string HOSTED_PROCESS = 'PU';
    public const string JOBS_EMAIL = 'ISM';
    public const string POSTAL_MAIL = 'MOD';
    public const string PREDEFINED_PROCESS = 'CL';
    public const string RECEIVED_FAX = 'FGFaxIn';
    public const string SENT_FAX = 'FGFaxOut';
    public const string RECEIVED_AS2 = 'IAS2';
    public const string RECEIVED_SFTP = 'IFTP';
    public const string SMS = 'Sms';
    public const string STORAGE = 'GARC';
    public const string TABLE_PROCESS = 'CT#<xxx>';
    public const string WEBFORM = 'USF';
}