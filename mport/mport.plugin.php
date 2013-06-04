<?php

/**
 *  Myportfolio plugin
 *
 *  @package Monstra
 *  @subpackage Plugins
 *  @author Moncho Varela / Nakome
 *  @copyright 2013-2014 Moncho Varela / Nakome
 *  @version 1.0.0
 *
 */

// Register plugin
Plugin::register( __FILE__,
                __('Myportfolio', 'mport'),
                __('Myportfolio plugin for Monstra', 'mport'),
                '1.0.0',
                'Nakome',
                'http://nakome.mapadesign.com/',
                'mport');

// Load mport Admin for Editor and Admin
if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {
    Plugin::admin('mport');
}


  /**
   * Add styles and javascript in frontend
   */
  Javascript::add('plugins/mport/lib/js/least.min.js','frontend',10);
  Javascript::add('plugins/mport/lib/js/jquery.lazyload.js','frontend',10);
  Stylesheet::add('plugins/mport/lib/css/least.min.css','frontend',11);

  // Shortcode
  Shortcode::add('Portfolio','mport::getStformport');

/**
 * mport class
 */
class mport extends Frontend{
    /**
     * echo mport::getPortfolio;
     */
    public static function getPortfolio(){
       $table = new Table('mport');
       $items = $table->select(null,'all');
       return view::factory('mport/views/frontend/index')->assign('items',$items)->display();
    }
    /**
     * {Porfolio}
     */
    public static function getStformport($attributes){
      extract($attributes);
      $table = new Table('mport');
      $items = $table->select(null,'all');
      return view::factory('mport/views/frontend/index')->assign('items',$items)->display();
    }
}
