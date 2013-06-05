<?php
/**
 * Deficiencias main start point.
*/

// Register class auto-loader
function deficiencias_autoloader($class_name)
{
	$file = str_replace('\\', '/', $class_name) . '.php';

	include('lib/'.strtolower($file));
}

spl_autoload_register('deficiencias_autoloader');

// Register global function for translating and to facilitate automatic
// generation of po files.
function t($text)
{
	static $language_object;
	
	if(!$language_object)
	{
		$language_object = new Cms\Language('locale');
	}
	
	return $language_object->Translate($text);
}

//Set directory where all the site data is stored as pages, users, groups, etc...
Cms\System::SetDataPath('data');

//Initializes the data directory and main settings
Cms\System::Init();

$page = Cms\Pages::Load(Cms\Uri::GetCurrent());

print $page->title;

/*
$page = new Page(Cms\PathHandler::GetCurrent());

Themes::Render($page);
 */
?>
