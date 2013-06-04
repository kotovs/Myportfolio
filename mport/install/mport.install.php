<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

Table::create('mport',array(
    'title',
    'info',
    'img',
));


// Options photos
Option::add('mport_width', 240);
Option::add('mport_height', 150);
Option::add('mport_wmax', 960);
Option::add('mport_hmax', 700);
Option::add('mport_resize', 'stretch');


// Folders
$dir    = ROOT.DS.'public' . DS . 'mport'. DS;
$small  = $dir . 'small' . DS;
$large  = $dir . 'large' . DS;



// Create folders if is dir
Dir::create($dir);
Dir::create($small);
Dir::create($large);

