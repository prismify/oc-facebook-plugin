<?php namespace Prismify\Facebook;

use Backend;
use System\Classes\PluginBase;

/**
 * Facebook Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Facebook',
            'description' => 'Drive traffic and engagement for your desktop and mobile web apps.',
            'author'      => 'Prismify, Algoriq',
            'icon'        => 'icon-facebook'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Prismify\Facebook\Components\Feed' => 'facebookFeed',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'prismify.facebook.access_settings' => [
                'tab' => 'Facebook',
                'label' => 'Manage Settings'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'facebook' => [
                'label'       => 'Facebook',
                'url'         => Backend::url('prismify/facebook/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['prismify.facebook.*'],
                'order'       => 500,
            ],
        ];
    }

    /**
     * Registers back-end settings for this plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Facebook',
                'description' => 'Manage Facebook settings.',
                'category'    => 'API Integrations',
                'icon'        => 'icon-cog',
                'class'       => 'Prismify\Facebook\Models\Settings',
                'order'       => 500,
                'keywords'    => 'facebook',
                'permissions' => ['prismify.facebook.access_settings']
            ]
        ];
    }
}
