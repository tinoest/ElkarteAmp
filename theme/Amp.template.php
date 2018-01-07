<?php

/**
 * @name      ElkArte Forum
 * @copyright ElkArte Forum contributors
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * This file contains code covered by:
 * copyright:	2011 Simple Machines (http://www.simplemachines.org)
 * license:  	BSD, See included LICENSE.TXT for terms and conditions.
 *
 * @version 1.1
 *
 */

/**
 * Interface for the bit up the amp page.
 */
function template_amp_above()
{
	global $context, $txt;

	$txt['amp_page_images'] = 'Images';
	$txt['amp_page_text']		= 'Text';

	echo '<!doctype html>
	<html amp> 
	<head>
		<meta charset="utf-8">
		<script async src="https://cdn.ampproject.org/v0.js"></script>
		<link rel="canonical" href="', $context['canonical_url'], '" />
		<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="robots" content="noindex" />
		<title>', $txt['amp_page'], ' - ', $context['topic_subject'], '</title>
		<style amp-custom>
			body, html {
				margin: 1em;
			}
			body, a {
				color: #000;
				background: #fff;
			}
			body, td, .normaltext {
				font-family: Verdana, arial, helvetica, serif;
				font-size: small;
			}
			h1#title {
				font-size: large;
				font-weight: bold;
			}
			h2#linktree {
				margin: 1em 0 2.5em 0;
				font-size: small;
				font-weight: bold;
			}
			dl#posts {
				width: 90%;
				margin: 0;
				padding: 0;
				list-style: none;
			}
			div.postheader, #poll_data {
				border: solid #000;
				border-width: 1px 0;
				padding: 4px 0;
			}
			div.postbody {
				margin: 1em 0 2em 2em;
			}
			div.fixed-container {
				position: relative;
				width: 90vw;
				height: 90vh;
			}
			table {
				empty-cells: show;
			}
			blockquote, code {
				border: 1px solid #000;
				margin: 3px;
				padding: 1px;
				display: block;
			}
			code {
				font: x-small monospace;
			}
			blockquote {
				font-size: x-small;
			}
			.smalltext, .quoteheader, .codeheader {
				font-size: x-small;
			}
			.largetext {
				font-size: large;
			}
			.centertext {
				text-align: center;
			}
			hr {
				height: 1px;
				border: 0;
				color: black;
				background: black;
			}
			.voted {
				font-weight: bold;
			}
			@media amp {
				.amp_options {
					display:none;
				}
			}
			@media screen {
				.amp_options {
					margin:1em;
				}
			}
			amp-img.contain img {
				object-fit: contain;
			}
		</style>
		<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
	</head>
	<body>
		<div class="amp_options">';

	// Which option is set, text or text&images
	if (!empty($context['viewing_attach']))
		echo '
			<a href="', $context['view_attach_mode']['text'], '">', $txt['amp_page_text'], '</a> | <strong><a href="', $context['view_attach_mode']['images'], '">', $txt['amp_page_images'], '</a></strong>';
	else
		echo '
			<strong><a href="', $context['view_attach_mode']['text'], '">', $txt['amp_page_text'], '</a></strong> | <a href="', $context['view_attach_mode']['images'], '">', $txt['amp_page_images'], '</a>';

	echo '
		</div>
		<h1 id="title">', $context['forum_name_html_safe'], '</h1>
		<h2 id="linktree">', $context['category_name'], ' => ', (!empty($context['parent_boards']) ? implode(' => ', $context['parent_boards']) . ' => ' : ''), $context['board_name'], ' => ', $txt['topic_started'], ': ', $context['poster_name'], ' ', $txt['search_on'], ' ', $context['post_time'], '</h2>
		<div id="posts">';
}

/**
 * The topic may have a poll
 */
function template_amp_poll_above()
{
	global $context, $txt;

	if (!empty($context['poll']))
	{
		echo '
			<div id="poll_data">', $txt['poll'], '
				<div class="question">', $txt['poll_question'], ': <strong>', $context['poll']['question'], '</strong>';

		$amp_options = 1;
		foreach ($context['poll']['options'] as $option)
			echo '
					<div class="', $option['voted_this'] ? 'voted' : '', '">', $txt['option'], ' ', $amp_options++, ': <strong>', $option['option'], '</strong>
						', $context['allow_poll_view'] ? $txt['votes'] . ': ' . $option['votes'] . '' : '', '
					</div>';

		echo '
			</div>';
	}
}

/**
 * Interface for amp page central view.
 */
function template_amp_page()
{
	global $context, $txt, $scripturl, $topic;

	foreach ($context['posts'] as $post)
	{
		// Clean out non amp html
		$post['body'] = amp_tags($post['body']);
		echo '
			<div class="postheader">
				', $txt['title'], ': <strong>', $post['subject'], '</strong><br />
				', $txt['post_by'], ': <strong>', $post['member'], '</strong> ', $txt['search_on'], ' <strong>', $post['time'], '</strong>
			</div>
			<div class="postbody">
				', $post['body'];

		// Show attachment images
		if (!empty($context['printattach'][$post['id_msg']]))
		{
			echo '
				<hr />';

			foreach ($context['printattach'][$post['id_msg']] as $attach)
				echo '
					<div class="fixed-container"><amp-img class="contain" layout="fill" src="'.$scripturl . '?action=dlattach;topic=' . $topic . '.0;attach=' . $attach['id_attach'] . '"></amp-img></div>
				';
		}

		echo '
			</div>';
	}
}

/**
 * Interface for the bit down the amp page.
 */
function template_amp_below()
{
	global $txt, $context;

	echo '
		</div>
		<div class="amp_options">';

	// Show the text / image links
	if (!empty($context['viewing_attach']))
		echo '
			<a href="', $context['view_attach_mode']['text'], '">', $txt['amp_page_text'], '</a> | <strong><a href="', $context['view_attach_mode']['images'], '">', $txt['amp_page_images'], '</a></strong>';
	else
		echo '
			<strong><a href="', $context['view_attach_mode']['text'], '">', $txt['amp_page_text'], '</a></strong> | <a href="', $context['view_attach_mode']['images'], '">', $txt['amp_page_images'], '</a>';

	echo '
		</div>
		<div id="footer" class="smalltext">
			', theme_copyright(), '
		</div>
	</body>
</html>';
}

function amp_tags($post)
{

	$replacements = array(
		'<a href="([^"]+)" onclick="([^"]+)" class="([^"]+)">' => '<a href="$1" class="$3">',
		'<img src="([^"]+)" alt="" class="([^"]+)" \/>' => '<div class="fixed-container"><amp-img class="contain" layout="fill" src="$1" alt=""></amp-img></div>'
	);

	foreach($replacements as $key => $value) {
		$post = preg_replace('/' . $key . '/isU', $value, $post);
	}

	return $post;
}
