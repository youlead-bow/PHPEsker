<?php

namespace Esker\Common;

/**
 * Class Transport
 * @package Esker\Common
 */
class Transport
{
    public const string AUDIT = 'Audit';
    public const string COPY_FILE = 'Copy';
    public const string EMAIL = 'Mail';
    public const string FORM = 'CustomData';
    public const string HOSTED_PROCESS = 'Pickup';
    public const string JOBS_EMAIL = 'MailRecv';
    public const string POSTAL_MAIL = 'MODEsker';
    public const string PREDEFINED_PROCESS = 'CmdLine';
    public const string RECEIVED_FAX = 'FaxRecv';
    public const string SENT_FAX = 'Fax';
    public const string RECEIVED_AS2 = 'InboundAS2';
    public const string RECEIVED_SFTP = 'InboundFtp';
    public const string SMS = 'Sms';
    public const string STORAGE = 'GARC';
    public const string TABLE_PROCESS = 'CustomTable';
    public const string WEBFORM = 'UserForm';

}