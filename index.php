<?php
/**
 * Deficiencies main start point.
*/

$time_start = microtime(true);

require_once 'lib/autoload.php';

//Set directory where all the site data is stored as pages, users, groups, etc...
Cms\System::SetDataPath('data');

//Initializes the data directory and main settings
Cms\System::Init();

Cms\System::SetHomePage('home');

Cms\System::SetTheme('deficiency');

$datasource = new \Cms\DBAL\DataSource;
$datasource->InitAsSQLite('deficiencies', \Cms\System::GetDataPath() . "sqlite");

Cms\System::SetDataSource($datasource);

//Setup the deficiency databse
Deficiencies\Setup::Database();

$page = Cms\Pages::Load(Cms\Uri::GetCurrent());

//Display the page
Cms\Theme::Render($page);

print "<div style=\"clear: both\">";
print "<div style=\"width: 90%; border: solid #f0b656 1px; background-color: #d0dde7; margin: 0 auto 0 auto; padding: 10px\">";
print "<b>Script execution time:</b> " . ceil((microtime(true) - $time_start) * 1000) . " milliseconds<br />";

print "<b>Peak memory usage:</b> " . number_format(memory_get_peak_usage() / 1024 / 1024, 0, '.', ',') . " MB<br />";
print "<b>Final memory usage:</b> " . number_format(memory_get_usage() / 1024 / 1024, 0, '.', ',') . " MB<br />";
print "</div>";

?>
