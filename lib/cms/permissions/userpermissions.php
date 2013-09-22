<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Cms\Permissions;

use Cms\Data\Permission;
use Cms\Data\PermissionsList;
use Cms\Enumerations\Permissions\Users;

/**
 * List of user management permissions.
 */
class UserPermissions extends PermissionsList
{
    public function __construct()
    {
        parent::__construct(t('Users'));
        
        $this->AddPermission(new Permission(
            Users::VIEW,
            t('View'),
            t('View all available user accounts from the users administrative page.')
        ));
        
        $this->AddPermission(new Permission(
            Users::CREATE,
            t('Create'),
            t('Add new user accounts.')
        ));
        
        $this->AddPermission(new Permission(
            Users::EDIT,
            t('Edit'),
            t('Modify existing user accounts.')
        ));
        
        $this->AddPermission(new Permission(
            Users::DELETE,
            t('Delete'),
            t('Delete existing user accounts.')
        ));
    }
}

?>
