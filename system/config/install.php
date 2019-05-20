<?php
// Site
$_['site_base']         = HTTP_SERVER;
$_['site_ssl']          = HTTP_SERVER;

// Language
$_['language_default']  = 'en-gb';
$_['language_autoload'] = array('en-gb');

// Session
$_['session_engine']    = 'file';
$_['session_autostart'] = true;
$_['session_name']      = 'PTSESSID';

// Template
$_['template_engine']   = 'html';
//$_['template_cache']    = true;

// Error
$_['error_display']     = true;

// Actions
$_['action_default']    = 'install/step_1';
$_['action_router']     = 'startup/router';
$_['action_error']      = 'error/not_found';
$_['action_pre_action'] = array(
	'startup/language',
	'startup/upgrade',
	'startup/database'
);

// Action Events
$_['action_event'] = array();
