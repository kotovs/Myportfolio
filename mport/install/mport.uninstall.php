<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

Table::drop('mport');

// Options photos
Option::delete('mport_width');
Option::delete('mport_height');
Option::delete('mport_wmax');
Option::delete('mport_hmax');
Option::delete('mport_resize');
