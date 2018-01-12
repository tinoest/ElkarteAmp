<?php

/**
* @package "AMP" Addon for Elkarte
* @author tinoest
* @license BSD http://opensource.org/licenses/BSD-3-Clause
*
* @version 1.0.0
*
*/

/**
 * Interface for AMP template.
 */
function template_amp_above()
{
	global $context, $txt, $settings;

	echo '<!doctype html>
	<html amp> 
	<head>
		<meta charset="utf-8">
		<script async src="https://cdn.ampproject.org/v0.js"></script>
		<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
		<link rel="canonical" href="', $context['canonical_url'], '" />
		<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="robots" content="noindex" />
		<title>', $txt['amp_page'], ' - ', $context['topic_subject'], '</title>
		<style amp-custom>
			body, html {
				margin: 1em;
			}
			header#top_section {
				border-top-color: #5ba048;
				border-bottom-color: #3d6e32;
				background: #f4f4f4;
				background-image: none;
				background-image: linear-gradient(to bottom,#fefefe,#eee);
				box-shadow: 0 1px 4px rgba(0,0,0,.3),0 1px 0 #38642d inset;
				padding: 0.5em;
			}
			body {
				background: #585858;
				background-image: none;
				background-image: linear-gradient(to right,#333 0,#888 50%,#333 100%);
				color: #585858;
			}			
			body, a {
				color: #000;
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
				background-image: linear-gradient(to right,#333 0,#888 50%,#333 100%);
				width: 90vw;
				margin: 0;
				padding: 0;
				list-style: none;
			}
			div.wrapper {
				border-color: #4b863c;
				border-top-color: rgb(75, 134, 60);
				border-bottom-color: rgb(75, 134, 60);
				border-top-color: #5ba048;
				border-bottom-color: #3d6e32;
				background: #fafafa;
				box-shadow: 0 2px 4px #111;
				padding: 1em;
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
				width: 85vw;
				height: 85vh;
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
			amp-img.contain img {
				object-fit: contain;
			}
			footer#footer_section {
				border-top-color: #3d6e32;
				background: #222;
				box-shadow: 0 -1px 0 #686868,0 1px 0 #0e0e0e inset;
				color: #bbb;
				padding: 1em;
			}
			footer a {
				color: #fff;
				background: #222;
				padding: 1em;
			}
			button {
				float: right;
			}
		</style>
		<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
	</head>
	<body>
		<header id="top_section">
			<amp-sidebar id="sidebar-right" layout="nodisplay" side="right">
			<ul>';
			foreach($context['board_list'] as $board) {
				echo '<li><a href="'.$board['href'].'">'.$board['name'].'</a></li>'.PHP_EOL;
				if(is_array($board['boards'])) {
					foreach($board['boards'] as $sub) {
						echo '<li><a href="'.$sub['href'].'">'.$sub['name'].'</a></li>'.PHP_EOL;
					}
				}
			}
			echo'
			</ul>
			</amp-sidebar>
			<button on="tap:sidebar-right.toggle" class="ampstart-btn caps m2"><img src="'. $settings['images_url'].'/list.jpg" width="24" height="24" alt="submit" /></button>
			<h1 id="title">', $context['forum_name_html_safe'], '</h1>
			<h2 id="linktree">', $context['category_name'], ' => ', (!empty($context['parent_boards']) ? implode(' => ', $context['parent_boards']) . ' => ' : ''), $context['board_name'], ' => ', $txt['topic_started'], ': ', $context['poster_name'], ' ', $txt['search_on'], ' ', $context['post_time'], '</h2>
		</header>
		<div class="wrapper">
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
		</div>
		<footer id="footer_section">
			', theme_copyright(), '
		</footer>
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
