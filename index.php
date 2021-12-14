<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.cassiopeia
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Template path
$templatePath = 'templates/' . $this->template;

// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName  = 'theme.' . $paramsColorName;
$wa->registerAndUseStyle($assetColorName, $templatePath . '/css/global/' . $paramsColorName . '.css');

// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles       = '';

if ($paramsFontScheme)
{
	if (stripos($paramsFontScheme, 'https://') === 0)
	{
		$this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', []);
		$this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', []);
		$this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style']);
		$wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'']);

		if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0)
		{
			$fontStyles = '--cassiopeia-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-family-headings: "' . str_replace('+', ' ', isset($matches[1][1]) ? $matches[1][1] : $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-weight-normal: 400;
			--cassiopeia-font-weight-headings: 700;';
		}
	}
	else
	{
		$wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'']);
		$this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
	}
}

// Enable assets
$wa->usePreset('template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.user')
	->useScript('template.user')
	->addInlineStyle(":root {
		--hue: 214;
		--template-bg-light: #f0f4fb;
		--template-text-dark: #495057;
		--template-text-light: #ffffff;
		--template-link-color: #2a69b8;
		--template-special-color: #001B4C;
		$fontStyles
	}");

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . Uri::root(true) . '/' . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES) . '" alt="' . $sitename . '">';
}
elseif ($this->params->get('siteTitle'))
{
	$logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = HTMLHelper::_('image', 'logo.svg', $sitename, ['class' => 'logo d-inline-block'], true, 0);
}

$hasClass = '';

if ($this->countModules('sidebar-left', true))
{
	$hasClass .= ' has-sidebar-left';
}

if ($this->countModules('sidebar-right', true))
{
	$hasClass .= ' has-sidebar-right';
}

// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

// Defer font awesome
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');

// MySoftware params
$userstyle = $this->params->get('userstyle');
$useuserstyle = $this->params->get('useuserstyle');
$mainbgcolor = $this->params->get('mainbgcolor');
$mainbgimage = $this->params->get('mainbgimage');
$colortext = $this->params->get('colortext');
$colorh = $this->params->get('colorh');
$color1 = $this->params->get('color1');
$color2 = $this->params->get('color2');
$color3 = $this->params->get('color3');
$color4 = $this->params->get('color4');
$color5 = $this->params->get('color5');
$color6 = $this->params->get('color6');
$mainbgrepeat = $this->params->get('mainbgrepeat');
$mainbgposx = $this->params->get('mainbgposx');
$mainbgposy = $this->params->get('mainbgposy');
$mainbgattachment = $this->params->get('mainbgattachment');
$bodyrow = $this->params->get('bodyrow');
$bodycol = $this->params->get('bodycol');

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
<?php
echo '<style type="text/css">'."\n";
echo 'body {'."\n";
if ($mainbgcolor<>'') echo '   background-color: '.$mainbgcolor.'; '."\n";
if ($mainbgimage<>'') echo '   background-image: url(/'.$mainbgimage.'); '."\n";
if ($colortext<>'') echo '   color: '.$colortext.'; '."\n";
if ($mainbgrepeat<>'') echo '   background-repeat: '.$mainbgrepeat.'; '."\n";
if ($mainbgposx<>'') echo '   background-position-x: '.$mainbgposx.'; '."\n";
if ($mainbgposy<>'') echo '   background-position-y: '.$mainbgposy.'; '."\n";
if ($mainbgattachment<>'') echo '   background-attachment: '.$mainbgattachment.'; '."\n";
echo '}'."\n";
echo 'h1,h2,h3,h4,h5,h6 {'."\n";
if ($colortext<>'') echo '   color: '.$colorh.'; '."\n";
echo '}'."\n";
if ($color1<>'') echo '.color1   {color: '.$color1.';} '."\n";
if ($color1<>'') echo '.bgcolor1   {background-color: '.$color1.';} '."\n";
if ($color2<>'') echo '.color2   {color: '.$color2.';} '."\n";
if ($color2<>'') echo '.bgcolor2   {background-color: '.$color2.';} '."\n";
if ($color3<>'') echo '.color3   {color: '.$color3.';} '."\n";
if ($color3<>'') echo '.bgcolor3   {background-color: '.$color3.';} '."\n";
if ($color4<>'') echo '.color4   {color: '.$color4.';} '."\n";
if ($color4<>'') echo '.bgcolor4   {background-color: '.$color4.';} '."\n";
if ($color5<>'') echo '.color5   {color: '.$color5.';} '."\n";
if ($color5<>'') echo '.bgcolor5   {background-color: '.$color5.';} '."\n";
if ($color6<>'') echo '.color6   {color: '.$color6.';} '."\n";
if ($color6<>'') echo '.bgcolor6   {background-color: '.$color6.';} '."\n";

if ($useuserstyle==1) echo "\n\n".$userstyle."\n";
echo '</style>'."\n";

// Add Bootstrap
JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.loadCss', true, $this->direction);

?>
</head>

<body class="site <?php echo $option
	. ' ' . $wrapper
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($pageclass ? ' ' . $pageclass : '')
	. $hasClass
	. ($this->direction == 'rtl' ? ' rtl' : '');
?>">

<jdoc:include type="modules" name="header" />

<?php
  for  ($row = 1; $row <= 9; $row++) {
    $rowkind = $this->params->get('row'.$row.'kind'); // 0,1,2,3
    $rowstyle = $this->params->get('row'.$row.'style'); 
    $rowdesc = $this->params->get('row'.$row.'desc');

    if ($rowkind<>0)
	{
	echo '<!-- row'.$row.' -->'."\n";
	echo ' <div id="fullrow'.$row.'"';
         if ($rowstyle<>'') echo ' class="'.$rowstyle.'"';
	echo '>'."\n";

	if ($rowkind==1) echo '<div class="container">'."\n";
	if ($rowkind==2) echo '<div class="container-fluid">'."\n";
//	if ($rowkind==3) echo '<div class="container-user">'."\n";
	if ($rowkind==3) {
	$file = 'custom/row'.$row.'.php';
	include($file);
	echo "\n";
	}
	else {
	echo ' <div id="row'.$row.'" class="';
	if ($rowkind==1) echo 'row';
	if ($rowkind==2) echo 'row-fluid';
        if ($rowstyle<>'') echo ' '.$rowstyle;
	echo '">'."\n";
	}

	if ($rowkind<3)
	for  ($col = 1; $col <= 6; $col++) {
		$mn='row'.$row.'col'.$col;
		$placestyle = $this->params->get($mn.'style');
		$countm = $this->countModules($mn);
		$bodyhere = ($row==$bodyrow)&&($col==$bodycol);

		if($countm||$bodyhere) echo '<div id="'.$mn.'" class="'.$placestyle.'">'."\n";
		$comp=stripos($_SERVER['REQUEST_URI'],'component/');

		if($bodyhere)
			{
			if ($countm&&$comp<1) echo '<jdoc:include type="modules" name="'.$mn.'" style="block" />';
			echo '<!-- content -->'."\n";
			echo '	<div id="main" class="main-box">';
			echo '	<jdoc:include type="component" />';
			echo '	<jdoc:include type="message" />';
			echo '	</div>'."\n";
			echo '<!-- /content -->'."\n";
			}
			else
			{
			if($countm) echo '<jdoc:include type="modules" name="'.$mn.'" style="block" />';
			}

		if($countm||$bodyhere) echo '</div>'."\n";
		}
	if ($rowkind<3) {
	echo ' </div>'."\n";
	echo '</div>'."\n\n";
	}
	echo '</div>';
	echo '<!-- /row'.$row.' -->'."\n";
	}
  }

?>

<jdoc:include type="modules" name="footer" />        
<jdoc:include type="modules" name="debug" />        

</body>
</html>
