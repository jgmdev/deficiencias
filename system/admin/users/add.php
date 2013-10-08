<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0
    field: title
        <?=t('Add User')?>
    field;

    field: content
    <?php
        \Cms\Authentication::ProtectPage(Cms\Enumerations\Permissions\Users::CREATE);

        $form = new \Cms\Form('add-user');
        
        $form->SetGlobalFilter(new Cms\Form\Filter\Html());

        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function()
        {
            if(isset($_REQUEST['btnSave']))
            {
                if($_REQUEST['password'] == $_REQUEST['password_confirm'])
                {
                    if(Cms\Users::Exists($_REQUEST['username']))
                    {
                        Cms\Theme::AddMessage(
                            t('The username you provided is already in use by other user account.'), 
                            Cms\Enumerations\MessageType::ERROR
                        );
                        return;
                    }

                    if(Cms\Users::EmailTaken($_REQUEST['email']))
                    {
                        Cms\Theme::AddMessage(
                            t('The e-mail you provided is already in use by other user account.'), 
                            Cms\Enumerations\MessageType::ERROR
                        );
                        return;
                    }

                    $user = new \Cms\Data\User();
                    $user->username = $_REQUEST['username'];
                    $user->password = $_REQUEST['password'];
                    $user->email = $_REQUEST['email'];
                    $user->personal_text = $_REQUEST['personal_text'];
                    $user->gender = $_REQUEST['gender'];
                    $user->website = $_REQUEST['website'];
                    $user->birth_date = mktime(0, 0, 0, $_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year']);
                    $user->status = $_REQUEST['status'];
                    $user->group = $_REQUEST['group'];

                    Cms\Users::Add($user);

                    Cms\Theme::AddMessage(t('The user account was successfully created.'));

                    Cms\Uri::Go('admin/users');
                }
                else
                {
                    Cms\Theme::AddMessage(t('Password and Confirm Password doesn\'t match.'), \Cms\Enumerations\MessageType::ERROR);
                }
            }
            elseif(isset($_REQUEST["btnCancel"]))
            {
                Cms\Uri::Go('admin/users');
            }
        });

        $form->AddField(new Cms\Form\Field\Text(
            t('Username'), 'username', '',
            '', '', true
        ));

        $form->AddField(new Cms\Form\Field\Text(
            t('Fullname'), 'fullname', '',
            t('A name that others can see.'), '', true, false
        ));

        $form->AddField(new Cms\Form\Field\TextArea(
            t('Personal text'), 'personal_text', $user_data->personal_text,
            t('Writing displayed on the user profile page.'), '', false, false, 300
        ));

        $form->AddField(new Cms\Form\Field\Text(
            t('E-mail'), 'email', '',
            t('The email used in case the user forgots its password.'),
            '', true
        ));

        $form->AddField(new Cms\Form\Field\Password(
            t('Password'), 'password', '',
            '', '', true
        ));

        $form->AddField(new Cms\Form\Field\Password(
            t('Confirm password'), 'password_confirm', '',
            t('Re-enter the password to verify it.'), '', true
        ));

        $form->AddField(new Cms\Form\Field\Text(
            t('Website'), 'website', '',
            t('Corporate or personal website.')
        ));

        $gender_group = new Cms\Form\FieldsGroup(t('Gender'));
        $gender_group->AddField(new \Cms\Form\Field\Radio(
            '', 'gender', array(t('Male')=>'m', t('Female')=>'f'),
            'm', '', true
        ));

        $form->AddGroup($gender_group);

        $birthdate_group = new Cms\Form\FieldsGroup(t('Birthdate'));
        
        $birthdate_group->AddField(new Cms\Form\Field\Select(
            t('Day'), 'day', Cms\Utilities\Date::GetDays(),
            '', '', '', true
        ));
        
        $birthdate_group->AddField(new Cms\Form\Field\Select(
            t('Month'), 'month', Cms\Utilities\Date::GetMonths(),
            '', '', '', true
        ));
        
        $birthdate_group->AddField(new Cms\Form\Field\Select(
            t('Year'), 'year', Cms\Utilities\Date::GetYears(),
            '', '', '', true
        ));

        $form->AddGroup($birthdate_group);

        $groups_list = \Cms\Groups::GetList();
        $groups = array();
        foreach($groups_list as $group)
        {
            $groups[$group->name] = $group->machine_name;
        }

        $status_codes = array();
        foreach(Cms\Enumerations\UserStatus::GetAll() as $status_code)
        {
            $status_codes[Cms\Enumerations\UserStatus::GetLabel($status_code)] = $status_code;
        }

        $form->AddField(new Cms\Form\Field\Select(
            t('Group'), 'group', $groups,
            '', t('The group where the user belongs.')
        ));

        $form->AddField(new Cms\Form\Field\Select(
            t('Status'), 'status', $status_codes,
            '', t('The account status of this user.')
        ));

        $form->AddField(new Cms\Form\Field\Submit(t('Save'), 'btnSave'));

        $form->AddField(new Cms\Form\Field\Submit(t('Cancel'), 'btnCancel'));

        $form->Render();
    ?>
    field;
row;
