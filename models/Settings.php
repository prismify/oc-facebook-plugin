<?php namespace Prismify\Facebook\Models;

use Model;

class Settings extends Model
{
    public $implement = [
        'System.Behaviors.SettingsModel'
    ];

    public $settingsCode = 'prismify_facebook_settings';

    public $settingsFields = 'fields.yaml';
}