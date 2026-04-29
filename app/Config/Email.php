<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public $fromEmail  = 'kostregar@gmail.com';
    public $fromName   = 'KostRegar';
    public $protocol   = 'smtp';
    public $SMTPHost   = 'smtp.gmail.com';
    public $SMTPUser   = 'kostregar@gmail.com';
    public $SMTPPass   = 'app_password_anda'; // ganti dengan App Password dari Google
    public $SMTPPort   = 465;
    public $SMTPCrypto = 'ssl';
    public $mailType   = 'html';
    public $charset    = 'utf-8';
}