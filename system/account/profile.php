<?php
/**
 * Copyright 2008, Jefferson González (JegoYalu.com)
 * This file is part of Jaris CMS and licensed under the GPLPP,
 * check the LICENSE.txt file for version and details or visit
 * http://gplpp.org/license.
 *
 * @file Database file that stores the user edit page.
 */
//For security the file content is skipped from the world eyes :)
exit;
?>

row: 0
    field: title
    <?php
        if(!isset($_REQUEST['username']))
        {
            $_REQUEST['username'] = Cms\Authentication::GetUser()->username;
        }

        if(Cms\Authentication::GetUser()->username != $_REQUEST['username'])
        {
            print t('Edit User');
        }
        else
        {
            print t('My Account Details');
        }
    ?>
    field;

    field: content
    <?php
        if(!Cms\Authentication::IsUserLogged())
        {
            Cms\Authentication::ProtectPage();
        }

        if(!isset($_REQUEST['username']) || trim($_REQUEST['username']) == '')
        {
            $_REQUEST['username'] = Cms\Authentication::GetUser()->username;
        }
        elseif(Cms\Authentication::GetUser()->username != $_REQUEST['username'])
        {
            Cms\Authentication::ProtectPage(Cms\Enumerations\Permissions\Users::EDIT);
        }

        $form = new \Cms\Form('edit-user');
        
        $form->SetGlobalFilter(new Cms\Form\Filter\Html());

        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function()
        {
            if(isset($_REQUEST['btnSave']))
            {
                $user_data = Cms\Users::GetData($_REQUEST['username']);

                $user_data->fullname = $_REQUEST['fullname'];
                $user_data->email = $_REQUEST['email'];
                $user_data->website = $_REQUEST['website'];
                $user_data->gender = $_REQUEST['gender'];
                $user_data->personal_text = $_REQUEST['personal_text'];
                $user_data->birth_date = mktime(0, 0, 0, $_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year']);

                $previous_password = $user_data->password;
                $previous_user_status = $user_data->status;

                if(Cms\Authentication::GetGroup()->HasPermission(Cms\Enumerations\Permissions\Groups::EDIT))
                {
                    $user_data->group = $_REQUEST['group'] ? $_REQUEST['group'] : $user_data->group;
                    $user_data->status = $_REQUEST['status'] ? $_REQUEST['status'] : $user_data->status;
                }

                $error = false;

                if($_REQUEST['password'] != "" && $_REQUEST['password'] == $_REQUEST['confirm_password'])
                {
                    $user_data->password = crypt($_REQUEST['password']);
                }
                elseif($_REQUEST['password'] != '' && $_REQUEST['password'] != $_REQUEST['confirm_password'])
                {
                    Cms\Theme::AddMessage(t('The New password and Verify password doesn\'t match.'), Cms\Enumerations\MessageType::ERROR);
                    $error = true;
                }

                if(!$error)
                {

                    try
                    {   
                        $username = Cms\Authentication::GetUser()->username;
                        
                        Cms\Users::Edit($_REQUEST['username'], $user_data);
                        
                        Cms\Theme::AddMessage(t('Your changes have been successfully saved.'));
                        
                        if($user_data->password != $previous_password && 
                            $username == $_REQUEST['username']
                        )
                        {
                            Cms\Authentication::Login($_REQUEST['username'], $_REQUEST['password']);
                        }

                        if(Cms\Authentication::GetGroup()->HasPermission(Cms\Enumerations\Permissions\Users::EDIT))
                        {
                            //Send notification email to user if account was activated
                            if($previous_user_status == '0' && $_REQUEST['status'] == '1')
                            {
                                $to = array();
                                $to[$user_data->fullname] = $user_data->email;

                                $html_message = t('Your account has been activated.') . '<br /><br />';
                                $html_message .= t('Username:') . " " . $_REQUEST['username'] . '<br /><br />';
                                $html_message .= t('Login by visiting:') . ' <a target="_blank" href="' . Cms\Uri::GetUrl('login') . '">' . Cms\Uri::GetUrl('login') . '</a>';

                                Cms\Mail::Send($to, t('Account Activated'), $html_message);
                            }
                        }
                    }
                    catch(Exception $e)
                    {
                        Cms\Theme::AddMessage($e->getMessage(), Cms\Enumerations\MessageType::ERROR);
                    }
                }

                if($_REQUEST['username'] == Cms\Authentication::GetUser()->username)
                    Cms\Uri::Go('account/profile');
                else
                    Cms\Uri::Go('account/profile', array('username' => $_REQUEST["username"]));
            }
            elseif(isset($_REQUEST["btnCancel"]))
            {
                if(
                    Cms\Authentication::IsAdminLogged() &&
                    ($_REQUEST['username'] != Cms\Authentication::GetUser()->username)
                )
                {
                    Cms\Uri::Go('admin/users');
                }
                else
                {
                    Cms\Uri::Go('account');
                }
            }
        });

        if(Cms\Authentication::GetGroup()->HasPermission(Cms\Enumerations\Permissions\Users::DELETE))
        {
            Cms\Theme::AddTab(
                t('Delete'),
                'admin/users/delete',
                array(
                    'username'=>$_REQUEST['username']
                )
            );
        }

        $user_data = Cms\Users::GetData($_REQUEST['username']);

        $form->AddField(new Cms\Form\Field\Text(
            t('Username'), 'username', $_REQUEST['username'],
            '', '', false, true
        ));

        $form->AddField(new Cms\Form\Field\Text(
            t('Fullname'), 'fullname', $user_data->fullname,
            t('A name that others can see.'), '', true, false
        ));

        $form->AddField(new Cms\Form\Field\TextArea(
            t('Personal text'), 'personal_text', $user_data->personal_text,
            t('Writing displayed on your profile page.'), '', false, false, 300
        ));

        if(Cms\Authentication::GetGroup()->HasPermission(Cms\Enumerations\Permissions\Users::EDIT))
        {
            $form->AddField(new Cms\Form\Field\Text(
                t('E-mail'), 'email', $user_data->email,
                t('The email used in case you forgot your password or to contact you.'),
                '', true
            ));
        }
        else
        {
            $email_validator = new \Cms\Form\Validator\EmailValidator;
            $email_validator->SetErrorMessage(t('Please provide a valid e-mail address.'));
            $email = new Cms\Form\Field\Text(
                t('E-mail'), 'email', $user_data->email,
                t('The email used in case you forgot your password or to contact you.'),
                '', true
            );
            $email->SetValidator($email_validator);
            
            $form->AddField($email);
        }

        $form->AddField(new Cms\Form\Field\Password(
            t('New password'), 'password', '',
            t('You can enter a new optional password to change actual one.')
        ));

        $form->AddField(new Cms\Form\Field\Password(
            t('Confirm new password'), 'confirm_password', '',
            t('Re-enter the new password to verify it.')
        ));

        $form->AddField(new Cms\Form\Field\Text(
            t('Website'), 'website', $user_data->website,
            t('Corporate or personal website.')
        ));

        $gender_group = new Cms\Form\FieldsGroup(t('Gender'));
        $gender_group->AddField(new \Cms\Form\Field\Radio(
            '', 'gender', array(t('Male')=>'m', t('Female')=>'f'),
            (trim($user_data->gender)!=''?$user_data->gender:'m'), '', true
        ));

        $form->AddGroup($gender_group);
        
        $day = date("j", intval($user_data->birth_date));
        $month = date("n", intval($user_data->birth_date));
        $year = date("Y", intval($user_data->birth_date));

        $birthdate_group = new Cms\Form\FieldsGroup(t('Birthdate'));
        
        $birthdate_group->AddField(new Cms\Form\Field\Select(
            t('Day'), 'day', Cms\Utilities\Date::GetDays(),
            $day, '', '', true
        ));
        
        $birthdate_group->AddField(new Cms\Form\Field\Select(
            t('Month'), 'month', Cms\Utilities\Date::GetMonths(),
            $month, '', '', true
        ));
        
        $birthdate_group->AddField(new Cms\Form\Field\Select(
            t('Year'), 'year', Cms\Utilities\Date::GetYears(),
            $year, '', '', true
        ));

        $form->AddGroup($birthdate_group);

        //Display user group and status selector if user has permissions
        if(Cms\Authentication::GetGroup()->HasPermission(Cms\Enumerations\Permissions\Users::EDIT))
        {
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
                $user_data->group, t('The group where the user belongs.')
            ));

            $form->AddField(new Cms\Form\Field\Select(
                t('Status'), 'status', $status_codes,
                $user_data->status, t('The account status of this user.')
            ));
        }

        $form->AddField(new Cms\Form\Field\Submit(t('Save'), 'btnSave'));

        $form->AddField(new Cms\Form\Field\Submit(t('Cancel'), 'btnCancel'));

        if($user_data->ip)
        {
            print "<p>" . t("Last login from ip:") . " " . $user_data->ip . "</p>";
        }

        $form->Render();
    ?>
    field;
row;
