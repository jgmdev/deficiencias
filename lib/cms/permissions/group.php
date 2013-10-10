<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Permissions;

use Cms\Data\Permission;
use Cms\Data\PermissionsList;
use Cms\Enumerations\Permissions\Groups;

/**
 * List of group management permissions.
 */
class Group extends PermissionsList
{
    public function __construct()
    {
        parent::__construct(t('Groups'));
        
        $this->AddPermission(new Permission(
            Groups::VIEW,
            t('View'),
            t('View all available groups from the groups administrative page.')
        ));
        
        $this->AddPermission(new Permission(
            Groups::CREATE,
            t('Create'),
            t('Add new user accounts.')
        ));
        
        $this->AddPermission(new Permission(
            Groups::EDIT,
            t('Edit'),
            t('Modify existing user accounts.')
        ));
        
        $this->AddPermission(new Permission(
            Groups::DELETE,
            t('Delete'),
            t('Delete existing user accounts.')
        ));
    }
}

?>
