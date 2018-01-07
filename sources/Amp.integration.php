<?php

/**
 * @package "ElkAmp" Addon for Elkarte
 * @author tinoest
 * @license BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 1.0.0
 *
 */

if (!defined('ELK'))
{
	die('No access...');
}

/**
 * integrate_display_buttons hook, called from Display.controller
 *
 * - Used to add additional buttons to topic views
 */
function idb_elkAmp()
{
	global $context, $scripturl, $txt;

	$txt['amp'] = 'AMP';

	// Add Amp to options
	$context['normal_buttons']['amp'] = array(
		'test' => 'can_print',
		'text' => 'amp',
		'image' => 'print.png',
		'lang' => true,
		'custom' => 'rel="nofollow"',
		'class' => 'new_win',
		'url' => $scripturl . '?action=amp;topic=' . $context['current_topic'] . '.0'
	);

}
