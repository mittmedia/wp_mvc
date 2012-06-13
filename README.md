wp_mvc
======

A library for WordPress needed to empower this plugin:
https://github.com/fredriksundstrom/theme_stats

How to install
--------------

Put the wp_mvc dir somewhere on your computer/server and make sure it's in the include_path of php.ini.

How to use
----------

Load it up, fill it with config values and init()! (Please note the plugin name must be the same as the plugin folders name)

    require_once( 'wp_mvc/init.php' );

    $app = new \WpMvc\Application();
    $app->config->home_path( WP_PLUGIN_DIR . '/theme_stats' );
    $app->init();