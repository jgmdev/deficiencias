<?php
/** 
 * @author Jefferson González
 * @license MIT
*/

namespace Deficiencies;

use Cms\Data\Permission;

/**
 * List of user management permissions.
 */
class PermissionsList extends \Cms\Data\PermissionsList
{
    public function __construct()
    {
        parent::__construct(t('Deficiencies'));
        
        $this->AddPermission(new Permission(
            Permissions::ADMINISTRATOR,
            t('Administrator'),
            t('Has administrative permissions like selecting attendant cities...')
        ));
        
        $this->AddPermission(new Permission(
            Permissions::ATTENDANT,
            t('Attendant'),
            t('Has rights to work with reported deficiencies that has been assigned.')
        ));
        
        $this->AddPermission(new Permission(
            Permissions::VIEW,
            t('View'),
            t('View all available reported deficiencies.')
        ));
        
        $this->AddPermission(new Permission(
            Permissions::EDIT,
            t('Edit'),
            t('Modify a deficiency.')
        ));
        
        $this->AddPermission(new Permission(
            Permissions::ASSIGN,
            t('Assign'),
            t('Can assign a deficiency report to a user for follow ups.')
        ));
        
        $this->AddPermission(new Permission(
            Permissions::DELETE,
            t('Delete'),
            t('Delete deficiencies from the database.')
        ));
    }
}

?>
