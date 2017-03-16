<?php
/*
Plugin Name: WP Update Server
Description: The plugin that runs the BinaryTurf update API.
Version: 1.0
Author: Shivanand Sharma
Author URI: https:/www.binarytruf.com
Original Author: Yahnis Elsts
Original Author URI: http://w-shadow.com/
*/
//ini_set("display_errors", 1);

require_once __DIR__ . '/loader.php';


//require __DIR__ . '/loader.php';
//$server = new Wpup_UpdateServer();
//$server->handleRequest();

class BT_Update_Server {
    protected $updateServer;

    public function __construct() {
        $this->updateServer = new BT_Init_Server(home_url('/'));

        //The "action" and "slug" query parameters are often used by the WordPress core
        //or other plugins, so lets use different parameter names to avoid conflict.
        add_filter('query_vars', array($this, 'addQueryVariables'));
        add_action('template_redirect', array($this, 'handleUpdateApiRequest'));
    }

    public function addQueryVariables($queryVariables) {
        $queryVariables = array_merge($queryVariables, array(
            'action',
            'slug',
            'license',
        ));
        return $queryVariables;
    }

    public function handleUpdateApiRequest() {
        if ( get_query_var('action') && get_query_var('slug') ) {
            $this->updateServer->handleRequest(array(
                'action' => get_query_var('action'),
                'slug'   => get_query_var('slug'),
                'license'   => get_query_var('license'),
            ));
        }
    }
}

class BT_Init_Server extends Wpup_UpdateServer {
    protected function generateDownloadUrl(Wpup_Package $package) {
        $query = array(
            'action' => 'download',
            'slug' => $package->slug,
        );
        return self::addQueryArg($query, $this->serverUrl);
    }
}

$examplePlugin = new BT_Update_Server();