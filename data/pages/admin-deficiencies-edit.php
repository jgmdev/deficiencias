<?php
/** 
 * @author Jefferson González
 * @license MIT
*/
exit;
?>

row: 0

    field: title
        <?=t('Edit Report')?>
    field;
    
    field: content
    <?php
        Cms\Authentication::ProtectPage(Deficiencies\Permissions::EDIT);
        
        $form = new \Cms\Form('edit-deficiencies');
        
        $form->Listen(Cms\Enumerations\Signals\Form::SUBMIT, function()
        {
            try
            {
                $deficiency = Deficiencies\Reports::GetData($_REQUEST['id']);
                
                $deficiency->assigned_to = $_REQUEST['assigned_to'];
                $deficiency->comments = $_REQUEST['comments'];
                $deficiency->last_update = time();
                $deficiency->last_update_by = Cms\Authentication::GetUser()->username;
                $deficiency->latitude = $_REQUEST['latitude'];
                $deficiency->longitude = $_REQUEST['longitude'];
                $deficiency->priority = $_REQUEST['priority'];
                //$deficiency->reopened_count = $_REQUEST['reopened_count'];
                //$deficiency->report_day = $_REQUEST['report_day'];
                //$deficiency->report_month = $_REQUEST['report_month'];
                //$deficiency->report_timestamp = $_REQUEST['report_timestamp'];
                //$deficiency->report_year = $_REQUEST['report_year'];
                //$deficiency->reports_count = $_REQUEST['reports_count'];
                $deficiency->resolution_status = $_REQUEST['resolution_status'];
                $deficiency->status = $_REQUEST['status'];
                $deficiency->type = $_REQUEST['type'];
                //$deficiency->username = $_REQUEST['username'];
                $deficiency->work_comments = $_REQUEST['work_comments'];
                $deficiency->address->city = $_REQUEST['city'];
                $deficiency->address->country = $_REQUEST['country'];
                $deficiency->address->line1 = $_REQUEST['line1'];
                $deficiency->address->line2 = $_REQUEST['line2'];
                $deficiency->address->zipcode = $_REQUEST['zipcode'];
                
                Deficiencies\Reports::Edit($_REQUEST['id'], $deficiency);
                
                Cms\Theme::AddMessage(
                    t('Changes successfully saved.')
                );
                
                Cms\Uri::Go('admin/deficiencies');
            }
            catch(Exception $e)
            {
                Cms\Theme::AddMessage(
                    $e->getMessage(), 
                    Cms\Enumerations\MessageType::ERROR
                );
            }
        });
        
        $report = Deficiencies\Reports::GetData($_REQUEST['id']);
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Id'), 'id', $_REQUEST['id'], '', '', true, true
        ));
        
        if($report->last_update)
        {
            $form->AddField(new Cms\Form\Field\Text(
                t('Last update'), 'last_update', 
                date('j/n/Y', $report->last_update), 
                '', '', false, true
            ));
            
            if($report->last_update_by)
            {
                $fullname = Cms\Users::GetData($report->last_update_by)->fullname;

                if(!$fullname)
                    $fullname = $report->last_update_by;

                $form->AddField(new Cms\Form\Field\Text(
                    t('Last update by'), 'last_update_by', 
                    $fullname, '', '', false, true
                ));
            }
        }
        
        $types = array_flip(Deficiencies\Types::getAll());
        
        $form->AddField(new \Cms\Form\Field\Select(
            t('Type'), 'type', $types, $report->type, '', '', true
        ));
        
        $city_attendants = array(
                t('Not Assigned')=>''
            ) + 
            Deficiencies\Attendants::GetCityAttendants($report->address->city)
        ;
        
        if(count($city_attendants) == 1)
            Cms\Theme::AddMessage(
                t('No group of attendants have been assigned to this group.') .
                ' ' . t('You can not assign until this issue is solved.'),
                Cms\Enumerations\MessageType::ERROR
            );
        
        $form->AddField(new \Cms\Form\Field\Select(
            t('Assigned to'), 'assigned_to', $city_attendants, $report->assigned_to
        ));
        
        $status = array_flip(Deficiencies\Status::getAll());
        
        $form->AddField(new Cms\Form\Field\Select(
            t('Status'), 'status', $status, $report->status
        ));
        
        $resolution_status = array_flip(Deficiencies\ResolutionStatus::getAll());
        
        $form->AddField(new Cms\Form\Field\Select(
            t('Resolution Status'), 'resolution_status', 
            $resolution_status, $report->resolution_status
        ));
        
        $priority = array_flip(Deficiencies\Priority::getAll());
        
        $form->AddField(new Cms\Form\Field\Select(
            t('Priority'), 'priority', 
            $priority, $report->priority
        ));
        
        $cities = Deficiencies\Towns::GetAll();
        $cities = array(t('All')=>'') + $cities;
        
        $form->AddField(new Cms\Form\Field\Select(
            t('City'), 'city', $cities, $report->address->city
        ));
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Address line 1'), 'line1', $report->address->line1
        ));
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Address line 2'), 'line2', $report->address->line2
        ));
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Zipcode'), 'zipcode', $report->address->zipcode
        ));
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Country'), 'country', $report->address->country
        ));
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Latitude'), 'latitude', $report->latitude
        ));
        
        $form->AddField(new Cms\Form\Field\Text(
            t('Longitude'), 'longitude', $report->longitude
        ));
        
        $form->AddField(new Cms\Form\Field\TextArea(
            t('Work comments'), 'work_comments', $report->work_comments, 
            t('Comments you can add to keep track of things.')
        ));
        
        $form->AddField(new Cms\Form\Field\TextArea(
            t('Comments'), 'comments', $report->comments, 
            t('Comments made by main reporting user.')
        ));
        
        $form->AddField(new \Cms\Form\Field\Submit(t('Save'), 'btnSave'));
        
        $form->AddField(new \Cms\Form\Field\Submit(t('Cancel'), 'btnCancel'));
        
        $form->Render();
    ?>
    field;
    
row;