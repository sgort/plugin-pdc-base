<?php
/**
 * Add all the activation/registration/uninstall hooks for the WordPress eco system.
 */

namespace OWC\PDC\Base\Foundation;

/**
 * Add all the activation/registration/uninstall hooks for the WordPress eco system.
 */
class Hooks
{

    /**
     * This method is called when the plugin is being activated.
     *
     * @return void
     */
    public static function pluginActivation()
    {}

    /**
     * This method is called immediately after any plugin is activated, and may be used to detect the activation of
     * plugins. If a plugin is silently activated (such as during an update), this hook does not fire.
     *
     * @param string $plugin
     * @param bool $networkActivation
     *
     * @return void
     */
    public static function pluginActivated($plugin, $networkActivation)
    {}

    /**
     * This method is run immediately after any plugin is deactivated, and may be used to detect the deactivation of
     * other plugins.
     *
     * @param string $plugin
     * @param bool $networkDeactivation
     *
     * @return void
     */
    public static function pluginDeactivated($plugin, $networkDeactivation)
    {}

    /**
     * This method registers a plugin function to be run when the plugin is deactivated.
     *
     * @return void
     */
    public static function pluginDeactivation()
    {}

    /**
     * This method is run when the plugin is activated.
     * This method run is when the user clicks on the uninstall link that calls for the plugin to uninstall itself.
     * The link won’t be active unless the plugin hooks into the action.
     *
     * @return void
     */
    public static function uninstallPlugin()
    {}
}
