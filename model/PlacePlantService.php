<?php

require_once 'model/DbConfig.php';
require_once 'model/PlacePlant.php';
require_once 'model/ValidationException.php';


class PlacePlantService {

    // private $contact  = NULL;
    // private $place    = NULL;
    private $placePlant    = NULL;

    private function openDb() {
        if (!mysql_connect(DB_HOST, DB_USER, DB_PASS)) {
            throw new Exception("Connection to the database server failed!");
        }
        if (!mysql_select_db(DB_NAME)) {
            throw new Exception("No mvc-crud database found on database server.");
        }
        mysql_query("SET NAMES UTF8");
        mysql_query("SET character_set_results=utf8");
        mysql_query("SET character_set_client=utf8");
        mysql_query("SET character_set_connection=utf8");
    }

    private function closeDb() {
        mysql_close();
    }

    public function __construct() {
        // $this->place      = new Place();
        $this->placePlant = new placePlant();
        // $this->contact    = new Contact();
    }

    private function validatePlacePlantParams( $place_id, $plant_id, $plant_date ) {
        $errors = array();
        if ( !isset($place_id) || empty($place_id) ) {
            $errors[] = '$place_id is required';
        }
        if ( !isset($plant_id) || empty($plant_id) ) {
            $errors[] = '$plant_id is required';
        }
        if ( !isset($plant_date) || empty($plant_date) ) {
            $errors[] = '$plant_date is required';
        }

        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }
    //
    // public function getContact($id) {
    //     try {
    //         if($id != null){
    //           $contactObj = $this->contact->selectById($id);
    //         }
    //         else {
    //           $contactObj = null;
    //         }
    //         return $contactObj;
    //     } catch (Exception $e) {
    //         throw $e;
    //     }
    // }
    //
    // public function getPlacePlant($id) {
    //       try {
    //           if($id != null){
    //             $contactObj = $this->placePlant->selectByPlaceId($id);
    //           }
    //           else {
    //             $contactObj = null;
    //           }
    //           return $contactObj;
    //       } catch (Exception $e) {
    //           throw $e;
    //       }
    //   }
    //
    // public function getAllPlace($order) {
    //     try {
    //         $this->openDb();
    //
    //         $resPlaces = $this->place->selectAll($order);
    //         foreach($resPlaces as $key=>$value){
    //            $resContact = $this->getContact($value->contact_id);
    //            $value->{"contact"} = $resContact;
    //         }
    //
    //         $this->closeDb();
    //         return json_encode($resPlaces);
    //     } catch (Exception $e) {
    //         $this->closeDb();
    //         throw $e;
    //     }
    // }
    //
    // public function getPlace($id) {
    //     try {
    //         $this->openDb();
    //
    //         $resPlace = $this->place->selectById($id);
    //         if( $resPlace )
    //         {
    //           $resContact = $this->getContact($resPlace->contact_id);
    //           $resPlacePlant = $this->getPlacePlant($resPlace->place_id);
    //           $resPlace->{"contact"} = $resContact;
    //           $resPlace->{"place_plant"} = $resPlacePlant;
    //         }
    //         else{
    //             return json_encode(array('place_id' => null), JSON_FORCE_OBJECT);
    //         }
    //         $this->closeDb();
    //         return json_encode($resPlace);
    //     } catch (Exception $e) {
    //         $this->closeDb();
    //         throw $e;
    //     }
    //     return $this->place->find($id);
    // }

    public function addPlacePlant( $place_id, $placePlantArr ) {
        try {
            $this->openDb();
            foreach($placePlantArr as $key=>$value)
            {
              $plant_id = $value->plant_id;
              $plant_date = $value->plant_date;
              $this->validatePlacePlantParams( $place_id, $plant_id, $plant_date );
              $res = $this->placePlant->insert( $place_id, $plant_id, $plant_date );
            }
            $this->closeDb();
            if( $res)
            {
                return $res;
            }
            else{
                return json_encode(array('place_plant_id' => null), JSON_FORCE_OBJECT);
            }
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    public function addOrUpdatePlacePlant( $placePlantArr ) {
        try {
            $this->openDb();
            foreach($placePlantArr as $key=>$value)
            {
              $place_plant_id = $value->place_plant_id;
              $place_id = $value->place_id;
              $plant_id = $value->plant_id;
              $plant_date = $value->plant_date;
              $this->validatePlacePlantParams( $place_id, $plant_id, $plant_date);
              if($place_plant_id == null)
              {
                // echo 'add place_id='.$place_id.'  plant_id='.$plant_id;
                $res = $this->placePlant->insert( $place_id, $plant_id, $plant_date );
              } else {
                // echo 'update place_id='.$place_id.'  plant_id='.$plant_id.'  place_plant_id='.$place_plant_id;
                $res = $this->placePlant->update( $place_plant_id, $place_id, $plant_id, $plant_date );
              }
            }
            $this->closeDb();
            if( $res)
            {
                return $res;
            }
            else{
                return false;
            }
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    // public function updatePlacePlant( $place_plant_id, $place_id, $plant_id, $plant_date ) {
    //     try {
    //         $this->openDb();
    //         $this->validatePlacePlantParams( $place_id, $plant_id, $plant_date);
    //         $res = $this->placePlant->update( $place_plant_id, $place_id, $plant_id, $plant_date );
    //         $this->closeDb();
    //         if( $res)
    //         {
    //             return $res;
    //         }
    //         else{
    //             return false;
    //         }
    //     } catch (Exception $e) {
    //         $this->closeDb();
    //         throw $e;
    //     }
    // }

    // public function deletePlacePlant( $id ) {
    //     try {
    //         $this->openDb();
    //         $res = $this->placePlant->delete($id);
    //         $this->closeDb();
    //         return $res;
    //     } catch (Exception $e) {
    //         $this->closeDb();
    //         throw $e;
    //     }
    // }

    public function deleteByPlaceId( $id ) {
        try {
            $this->openDb();
            $res = $this->placePlant->deleteByPlaceId($id);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }
}

?>
