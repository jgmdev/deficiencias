<?php exit; ?>

row: 0

    field: title
        File to make tests
    field;
    
    field: content
    <?php
        use Cms\Enumerations\FieldType;
        
        $datasource = new Cms\DBAL\DataSource();
        $datasource->InitAsSQLite('geocodes', Cms\System::GetDataPath() . "sqlite");
        
        $table = new Cms\DBAL\Query\Table('geocodes');
        
        $table->AddTextField('address')
            ->AddRealField('lon')
            ->AddRealField('lat')
            ->AddPrimaryKey('address')
        ;
        
        $db = new Cms\DBAL\DataBase($datasource);
        
        $db->CreateTable($table);
        
        $insert = new Cms\DBAL\Query\Insert('geocodes');
        $insert->Insert('address', 'Una dirección xD\'', FieldType::TEXT)
            ->Insert('lon', 35.32, FieldType::REAL)
            ->Insert('lat', -35.32, FieldType::REAL)
        ;
        
        $select = new \Cms\DBAL\Query\Select('geocodes');
        $select->Select('lat')->Select('lon')->WhereEqual('address', 'wepale', FieldType::TEXT)->Limit(0, 15);
        print $select->GetSQL(\Cms\Enumerations\DBDataSource::SQLITE);
        
        $db->Insert($insert);
    ?>
    field;
    
row;