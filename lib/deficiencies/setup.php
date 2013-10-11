<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

use Cms;

/**
 * When modules functionality is implemented on the Cms this should
 * be moved to a seperate module.
 */
class Setup
{
    /**
     * Disable Constructor
     */
    private function __construct() {}
    
    public static function Init()
    {
        Cms\Signals\SignalHandler::Listen(Cms\Enumerations\Signals\System::INIT, function()
        {
            if(Cms\Uri::GetCurrent() == 'admin/groups/edit')
            {
                if(Cms\Authentication::GetGroup()->HasPermission(Permissions::ADMINISTRATOR))
                {
                    if(isset($_REQUEST['group']))
                    {
                        if(Attendants::GroupIsAttendant($_REQUEST['group']))                        
                            Cms\Theme::AddTab(
                                t('Assigned Cities'), 
                                'admin/deficiencies/attendants/cities',
                                array('group'=>$_REQUEST['group'])
                            );
                    }
                }
            }
        });
        
        Cms\Signals\SignalHandler::Listen(Cms\Enumerations\Signals\User::GENERATE_PAGE, function()
        {
            Cms\Theme::AddTab(t('My Reports'), 'account/reports');
        });
        
        Cms\Signals\SignalHandler::Listen(Cms\Enumerations\Signals\Group::GET_PERMISSIONS, function($signal_data)
        {
            $signal_data->permissions[] = new PermissionsList;
        });
        
        Cms\Signals\SignalHandler::Listen(Cms\Enumerations\Signals\Gui::GENERATE_CONTROL_CENTER, function($signal_data)
        {
            /* @var $page_groups \Cms\Data\PagesGroupList */
            $page_groups = $signal_data->page_groups;
            
            $page_view = new Cms\Data\Page('admin/deficiencies');
            $page_view->title = t('View');
            $page_view->description = t('All deficiencies reported.');
            $page_view->AddPermission(Permissions::VIEW);
            
            $page_attendants = new Cms\Data\Page('admin/deficiencies/attendants');
            $page_attendants->title = t('Attendants');
            $page_attendants->description = t('Manage the attendant cities.');
            $page_attendants->AddPermission(Permissions::ADMINISTRATOR);
            
            $page_groups->AddGroupBefore(
                t('Deficiencies'),
                array(
                    'admin/deficiencies'=>$page_view,
                    'admin/deficiencies/attendants'=>$page_attendants
                ),
                t('Users')
            );
        });
    }
    
    public static function Database()
    {
        $db = \Cms\System::GetRelationalDatabase();
        
        //Deficiency Table
        $deficiency_table = new \Cms\DBAL\Query\Table('deficiencies');
        
        $deficiency_table->AddIntegerField('id')
            ->AddIntegerField('type')
            ->AddTextField('username')
            ->AddRealField('latitude')
            ->AddRealField('longitude')
            ->AddTextField('photo')
            ->AddTextField('comments')
            ->AddIntegerField('reports_count')
            ->AddIntegerField('reopened_count')
            ->AddIntegerField('priority')
            ->AddTextField('assigned_to')
            ->AddIntegerField('resolution_status')
            ->AddIntegerField('status')
            ->AddTextField('work_comments')
            ->AddIntegerField('report_timestamp')
            ->AddIntegerField('report_day')
            ->AddIntegerField('report_month')
            ->AddIntegerField('report_year')
            ->AddIntegerField('last_update')
            ->AddTextField('last_update_by')
            ->AddTextField('line1')
            ->AddTextField('line2')
            ->AddTextField('zipcode')
            ->AddTextField('city')
            ->AddTextField('country')
            ->AddPrimaryKey('id')
        ;
        
        $db->CreateTable($deficiency_table);
    }
}
?>
