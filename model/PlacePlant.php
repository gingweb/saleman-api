<?php

/**
 * Table data gateway.
 *
 *  OK I'm using old MySQL driver, so kill me ...
 *  This will do for simple apps but for serious apps you should use PDO.
 */
class PlacePlant {

    // public function selectAll($order) {
    //     if ( !isset($order) ) {
    //         $order = "plant_id";
    //     }
    //     $dbOrder =  mysql_real_escape_string($order);
    //     $dbres = mysql_query("SELECT * FROM place_plant pp left join place pc on (pp.place_id = pc.place_id) LEFT JOIN
    // plant pnt ON (pp.plant_id = pnt.place_id) ORDER BY $dbOrder ASC");
    //     $places = array();
    //     while ( ($obj = mysql_fetch_object($dbres)) != NULL ) {
    //         $places[] = $obj;
    //     }
    //     return $places;
    // }

    public function selectByPlaceId($id) {
        $dbId =  mysql_real_escape_string($id);
        $dbres = mysql_query("SELECT * FROM place_plant pp LEFT JOIN plant pnt ON (pp.plant_id = pnt.plant_id) WHERE pp.place_id = $dbId ORDER BY pnt.plant_name ASC");
        $placePlants = array();
        if ($dbres)
        {
            while ( ($obj = mysql_fetch_object($dbres)) != NULL ) {
                $placePlants[] = $obj;
            }
        } else {
          //  $dbres is null
        }
        return $placePlants;
    }
    //
    // public function selectById($id) {
    //     $dbId = mysql_real_escape_string($id);
    //     $dbres = mysql_query("SELECT * FROM place_plant pp left join place pc on (pp.place_id = pc.place_id) LEFT JOIN
    // plant pnt ON (pp.plant_id = pnt.place_id) where place_plant_id=$dbId");
    //     $result = mysql_fetch_object($dbres);
    //     if($result){
    //        return $result;
    //     }else{
    //       return false;
    //     }
    // }

    public function insert( $place_id, $plant_id, $plant_date  ) {

        $dbPlaceId = ($place_id != NULL)?"'".mysql_real_escape_string($place_id)."'":'NULL';
        $dbPlantId = ($plant_id != NULL)?"'".mysql_real_escape_string($plant_id)."'":'NULL';
        $dbPlantDate = ($plant_date != NULL)?"'".mysql_real_escape_string($plant_date)."'":'NULL';

        $lastQuery = mysql_query("INSERT INTO place_plant ( place_id, plant_id, plant_date) VALUES ($dbPlaceId, $dbPlantId, $dbPlantDate)");
        if($lastQuery){
           return mysql_insert_id();
        }else{
          return false;
        }
    }

    public function update( $place_plant_id, $place_id, $plant_id, $plant_date ) {

      $dbPlacePlantId = ($place_plant_id != NULL)?"'".mysql_real_escape_string($place_plant_id)."'":'NULL';
      $dbPlaceId = ($place_id != NULL)?"'".mysql_real_escape_string($place_id)."'":'NULL';
      $dbPlantId = ($plant_id != NULL)?"'".mysql_real_escape_string($plant_id)."'":'NULL';
      $dbPlantDate = ($plant_date != NULL)?"'".mysql_real_escape_string($plant_date)."'":'NULL';

      $lastQuery = mysql_query(" UPDATE place_plant SET place_id = $dbPlaceId, plant_id = $dbPlantId, plant_date = $dbPlantDate WHERE place_plant_id = $dbPlacePlantId");
      if($lastQuery){
         return $this->selectByPlaceId($place_id);
      }else{
        return false;
      }
    }

    // public function delete($id) {
    //     $dbId = mysql_real_escape_string($id);
    //     mysql_query("DELETE FROM place WHERE place_id=$dbId");
    // }

    public function deleteByPlaceId($id) {
        $dbId = mysql_real_escape_string($id);
        mysql_query("DELETE FROM place_plant WHERE place_id=$dbId");
    }


}

?>
