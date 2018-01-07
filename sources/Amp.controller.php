<?php

/**
* @package "AMP" Addon for Elkarte
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
 * AMP Controller
 */
class AMP_Controller extends Action_Controller
{

	/**
	 * Entry point for this class (by default).
	 *
	 * @see Action_Controller::action_index()
	 */
	public function action_index()
	{
		$this->action_amp();
	}

	/**
	 * Format a topic to be amp friendly.
	 *
	 * What id does:
	 * - Must be called with a topic specified.
	 * - Accessed via ?action=topic;sa=amp.
	 *
	 * @uses template_amp_page() in Printpage.template,
	 * @uses template_amp_above() later without the main layer.
	 * @uses template_amp_below() without the main layer
	 */
	public function action_amp()
	{
		global $topic, $scripturl, $context, $user_info, $board_info, $modSettings;

		// Redirect to the boardindex if no valid topic id is provided.
		if (empty($topic))
			redirectexit();

		// Its not enabled, give them the boot
		if (!empty($modSettings['disable_print_topic']))
		{
			unset($this->_req->query->action);
			$context['theme_loaded'] = false;
			throw new Elk_Exception('feature_disabled', false);
		}

		// Clean out the template layers
		$template_layers = Template_Layers::instance();
		$template_layers->removeAll();

		// Get the topic starter information.
		require_once(SUBSDIR . '/Topic.subs.php');
		$topicinfo = getTopicInfo($topic, 'starter');

		// Redirect to the boardindex if no valid topic id is provided.
		if (empty($topicinfo))
			redirectexit();

		$context['user']['started'] = $user_info['id'] == $topicinfo['id_member'] && !$user_info['is_guest'];

		// Whatever happens don't index this.
		$context['robot_no_index'] = true;

		// @todo this code is almost the same as the one in Display.controller.php
		if ($topicinfo['id_poll'] > 0 && !empty($modSettings['pollMode']) && allowedTo('poll_view'))
		{
			loadLanguage('Post');
			require_once(SUBSDIR . '/Poll.subs.php');

			loadPollContext($topicinfo['id_poll']);
			$template_layers->addAfter('print_poll', 'print');
		}

		// Lets "output" all that info.
		loadTemplate('Amp');
		$template_layers->add('amp');
		$context['sub_template']	= 'amp_page';
		$context['board_name']		= $board_info['name'];
		$context['category_name'] = $board_info['cat']['name'];
		$context['poster_name']		= $topicinfo['poster_name'];
		$context['post_time']			= standardTime($topicinfo['poster_time'], false);
		$context['parent_boards'] = array();

		foreach ($board_info['parent_boards'] as $parent)
		{
			$context['parent_boards'][] = $parent['name'];
		}

		// Split the topics up so we can print them.
		$context['posts']	= topicMessages($topic);
		$posts_id = array_keys($context['posts']);

		if (!isset($context['topic_subject']))
			$context['topic_subject'] = $context['posts'][min($posts_id)]['subject'];

		// Fetch attachments so we can print them if asked, enabled and allowed
		if (isset($this->_req->query->images) && !empty($modSettings['attachmentEnable']) && allowedTo('view_attachments'))
		{
			require_once(SUBSDIR . '/Topic.subs.php');
			$context['printattach'] = messagesAttachments(array_keys($context['posts']));
			$context['viewing_attach'] = true;
		}

		// Set a canonical URL for this page.
		$context['canonical_url'] = $scripturl . '?topic=' . $topic . '.0';
		$context['view_attach_mode'] = array(
			'text' => $scripturl . '?action=amp;topic=' . $topic . '.0',
			'images' => $scripturl . '?action=amp;topic=' . $topic . '.0;images',
		);
	}

}


