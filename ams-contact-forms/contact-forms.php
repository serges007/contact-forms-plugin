<?php

/*
Plugin Name: Contact plugin
Plugin URI: http://www.amscorporations.com
Description: Un plugin pour formulaire de contact pour le développement sous WordPress
Version: 0.1
Author: Mbele Amoungui serge
Author URI: http://www.sergesmbele.be
License: GPL2
*/
//equivalent de zero_plugins

class  Contact_Plugin{
    public function __construct(){
        //inclusion des fichiers du plugin
        include_once plugin_dir_path( __FILE__ ).'/Contact_Page_Title.php';
        include_once plugin_dir_path( __FILE__ ).'/AMS_Contact.php';
        //instanciation des class contenu dans les fichiers
        add_action('admin_menu', array($this, 'add_admin_menu'));
        new Contact_Page_Title();
        new AMS_Contact();
        //
        register_activation_hook(__FILE__, array('AMS_Contact', 'install'));
        register_deactivation_hook(__FILE__, array('AMS_Contact', 'deactivate')); 
        //desinstallation du plugin toujours à la fin du constructeur
        register_uninstall_hook(__FILE__, array('AMS_Contact', 'uninstall'));
    }
    

    public function add_admin_menu(){
        add_menu_page('Notre plugin de formulaire de contact', 'Contact Forms', 'manage_options', 'ams', array($this, 'menu_html'));
    }    
    
    
    public function menu_html(){
        echo '<h1>'.get_admin_page_title().'</h1>';
        echo '<p>Bienvenue sur la page d\'accueil du plugin Contact Forms</p>';
    }    
}

new Contact_Plugin();


