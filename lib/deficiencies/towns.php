<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Deficiencies;

/**
 * List of towns in Puerto Rico.
 */
class Towns
{
    /**
     * Gets a sorted array of towns.
     * @return array
     */
    function GetAll()
    {
        $pueblos['Humacao'] = "humacao";
        $pueblos['Caguas'] = "caguas";
        $pueblos['Las Piedras'] = "las_piedras" ;
        $pueblos['Yabucoa'] = "yabucoa";
        $pueblos['Maunabo'] = "maunabo";
        $pueblos['Ceiba' ] = "ceiba";
        $pueblos['Fajardo'] = "fajardo";
        $pueblos['Luquillo'] = "luquillo";
        $pueblos['San Lorenzo'] = "san_lorenzo" ;
        $pueblos['Gurabo'] = "gurabo";
        $pueblos['Juncos'] = "juncos";
        $pueblos['Canóvanas' ] = "canovanas";
        $pueblos['Patillas'] = "patillas";
        $pueblos['San Juan'] = "san_juan";
        $pueblos['Cayey'] = "cayey" ;
        $pueblos['Jayuya'] = "jayuya";
        $pueblos['Juana Díaz'] = "juana_diaz";
        $pueblos['Aguas Buenas' ] = "aguas_buenas";
        $pueblos['Arecibo'] = "arecibo";
        $pueblos['Coamo'] = "coamo";
        $pueblos['Trujillo Alto'] = "trujillo_alto" ;
        $pueblos['Toa Baja'] = "toa_baja";
        $pueblos['Toa Alta'] = "toa_alta";
        $pueblos['Vega Baja' ] = "vega_baja";
        $pueblos['Vega Alta'] = "vega_alta";
        $pueblos['Rincón' ] = "rincon";
        $pueblos['Manatí'] = "manati";
        $pueblos['Ponce'] = "ponce";
        $pueblos['Hormigueros'] = "hormigueros" ;
        $pueblos['Florida'] = "florida";
        $pueblos['Mayagüez'] = "mayaguez";
        $pueblos['Barceloneta' ] = "barceloneta";
        $pueblos['Bayamón'] = "bayamon";
        $pueblos['Carolina'] = "carolina";
        $pueblos['Camuy'] = "camuy" ;
        $pueblos['Corozal'] = "corozal";
        $pueblos['Barranquitas'] = "barranquitas";
        $pueblos['Hatillo' ] = "hatillo";
        $pueblos['Maricao'] = "maricao";
        $pueblos['Aibonito'] = "aibonito";
        $pueblos['San Germán' ] = "san_german";
        $pueblos['Moca'] = "moca";
        $pueblos['Morovis'] = "morovis";
        $pueblos['Yauco' ] = "yauco";
        $pueblos['Isabela'] = "isabela";
        $pueblos['Vieques'] = "vieques";
        $pueblos['Peñuelas' ] = "penuelas";
        $pueblos['Culebra'] = "culebra";
        $pueblos['Adjuntas'] = "adjuntas";
        $pueblos['Aguada' ] = "aguada";
        $pueblos['Arroyo'] = "arroyo";
        $pueblos['Añasco'] = "anasco";
        $pueblos['Aguadilla' ] = "aguadilla";
        $pueblos['Cabo Rojo'] = "cabo_rojo";
        $pueblos['Cataño'] = "catano";
        $pueblos['Ciales' ] = "ciales";
        $pueblos['Cidra'] = "cidra";
        $pueblos['Comerío'] = "comerio";
        $pueblos['Dorado' ] = "dorado";
        $pueblos['Guánica'] = "guanica";
        $pueblos['Guayama'] = "guayama";
        $pueblos['Guayanilla' ] = "guayanilla";
        $pueblos['Guaynabo'] = "guaynabo";
        $pueblos['Lajas'] = "lajas";
        $pueblos['Lares' ] = "lares";
        $pueblos['Las Marías'] = "las_marias";
        $pueblos['Loíza'] = "loiza";
        $pueblos['Naguabo' ] = "naguabo";
        $pueblos['Naranjito'] = "naranjito";
        $pueblos['Orocovis'] = "orocovis";
        $pueblos['Quebradillas' ] = "quebradillas";
        $pueblos['Río Grande'] = "rio_grande";
        $pueblos['Sábana Grande'] = "sabana_grande";
        $pueblos['Santa Isabel' ] = "santa_isabel";
        $pueblos['San Sebastián' ] = "san_sebastian";
        $pueblos['Utuado' ] = "utuado";
        $pueblos['Villalba' ] = "villalba";
        $pueblos['Salinas' ] = "salinas";

        //Prepare pueblos array for sorting
        $key = 0;

        foreach($pueblos as $caption=>$value)
        {
            $pueblos_array[] = array("caption"=>$caption, "value"=>$value);
            $labels[$key] = $caption;
            $values[$key] = $value;

            $key++;
        }

        //Sort pueblos array
        array_multisort($labels, SORT_ASC, $values, SORT_ASC, $pueblos_array);

        unset($pueblos);

        //Store sorted pueblos array
        foreach($pueblos_array as $fields)
        {
            $pueblos[$fields["caption"]] = $fields["value"];
        }

        return $pueblos;
    }
}
?>
