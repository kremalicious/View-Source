<?php 
include_once('../_global/geshi/geshi.php');

function markupLinks($html,$uri)
{
	preg_match('/https?:\/\/\S+?(?=\/)/i', $uri, $matches);
	$baseUri = $matches[0];

	$regex = array('/(?<=&quot;)(https?:\/\/\S+)(?=&quot;)/i',	// Absolute paths: &quot;http://asdf.com/asdf.php?id=x&ad=y&quot;
				   '/(?<=&quot;)(\/\S+)(?=&quot;)/i');			// Relative paths: &quot;/scripts/regex-1.1.min.js&quot;
	
	$replc = array('<a href="$1">$1</a>',
				   "<a href=\"$baseUri$1\">$1</a>");

	return preg_replace($regex, $replc, $html);
}

$uri = $_GET['uri'];

if ($_POST)
{
	$data = $_POST['DOM'];
	$htmlenc = urldecode($data);
	
	$geshi = new GeSHi($htmlenc, 'html5');
	$geshi->enable_keyword_links(false);
	$geshi->enable_classes();
	$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
	$geshi->set_line_style('background: #E8EDF4;', 'background: #E4E8EF;', true);
	
	$htmlenc = $geshi->parse_code();
	
	// Substitute tabs for 4 spaces
	$htmlenc = str_replace("\t", '    ', $htmlenc);
	
	// Trim trailing spaces
	$htmlenc = preg_replace("/[ \t]+$/", '', $htmlenc);
	
	// Markup URIs and paths as links
	$htmlenc = markupLinks($htmlenc, $uri);	
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />
	<title>Source of <?php echo htmlspecialchars($uri); ?></title>
	<style>
		<?php echo $geshi->get_stylesheet(); ?>
		/**
		 * GeSHi CSS Inspired by 
		 * TextMate Theme Dawn
		 *
		 * Copyright 2008 Mark Story 
		 * 
		 * This work is licensed under the Creative Commons Attribution-Share Alike 2.5 Canada License. 
		 * To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/2.5/ca/ 
		 * or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
		 *
		 * @copyright		Copyright 2008, Mark Story.
		 * @link			http://mark-story.com/downloads/view/geshi-css-pack
		 * @license			http://creativecommons.org/licenses/by-sa/2.5/ca/
		 * 
		 * @modified		by Matthias Kretschmann to work nicely on iPad
		 */
		
		/*
		* Base
		**********************/
		html, body {
			background: #E8EDF4;
		}
		
		body,
		pre,
		li {
			font-family: 'Courier';
		}
		
		h1 {
			font-family: 'Gill Sans';
			color: #ccc;
			font-size: 24px;
			font-weight: bold;
			border-bottom: 3px solid #ccc;
		}
		
		/*
		* Global geshi styles
		**********************/
		pre.html5 {
			overflow: auto;
			white-space: pre-wrap;
			word-wrap: break-word;
			line-height: 1.7em;
			font-size: 13px;
			padding: 0 0 0 .5em;
			background: #E8EDF4;
		}
		pre.html5 ol {
			list-style: decimal;
			list-style-position: outside;
			padding: 0;
			margin: 0;
		}
		pre.html5 ol li {
			margin: 0 0 0 35px;
			padding: 0;
			color: #333;
			clear: none;
			
		}
		.html5 ol li div {
			color:#000;
		}
		
		.html5 .li1,
		.html5 .li2 {
			padding: 0 .5em;
			color: #666;
			font-weight: normal !important;
		}
		
		.html5 .li1 div,
		.html5 .li2 div {
			color: #333;
		}
		
		
		/* comments */
		.html5 .co1,
		.html5 .coMULTI {
			color:#5A526E;	
		}
		/* methods */
		.html5 .me1{
			color:#000;
		}
		.html5 .me0 {	
		
		}
		.html5 .me2 {	
			color:#000;
		}
		
		/* brackets */
		.html5 .br0 {
			color:#000;
		}
		
		/* strings */
		.html5 .st0 {
			color:#0B6125;
		}
		
		/* keywords */
		.html5 .kw1 {
			color: #794938;
		}
		.html5 .kw2 {
			color:#A71D5D;
			font-style: italic;		
		}
		
		.html5 .kw3 {
			color:#693A17;
		}
		
		/* numbers */
		.html5 .nu0 {
			color:#811F24;
		}
		
		/* vars */
		.html5 .re0 {
			color:#434A97;
		}

		
	</style>
</head>
<body>
<h1>Source of <?php echo htmlspecialchars($uri); ?></h1>
<?php echo $htmlenc; ?>
</body>
</html>