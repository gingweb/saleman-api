<?php

require_once 'model/DbConfig.php';
require_once 'model/Place.php';
require_once 'model/Contact.php';
require_once 'model/PlacePlant.php';
require_once 'model/ValidationException.php';


class PlaceService {

    private $contact  = NULL;
    private $place    = NULL;
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
        $this->place      = new Place();
        $this->placePlant = new placePlant();
        $this->contact    = new Contact();
    }

    private function validatePlaceParams( $place_type_id, $place_name, $place_des, $lat, $lng, $phone, $contact_id, $addr, $province_id, $district_id, $order_total, $emp_id ) {
        $errors = array();
        if ( !isset($place_name) || empty($place_name) ) {
            $errors[] = 'Name is required';
        }
        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }

    public function getContact($id) {
        try {
            if($id != null){
              $contactObj = $this->contact->selectById($id);
            }
            else {
              $contactObj = null;
            }
            return $contactObj;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPlacePlant($id) {
          try {
              if($id != null){
                $contactObj = $this->placePlant->selectByPlaceId($id);
              }
              else {
                $contactObj = null;
              }
              return $contactObj;
          } catch (Exception $e) {
              throw $e;
          }
      }

    public function getAllPlace($order) {
        try {
            $this->openDb();

            $resPlaces = $this->place->selectAll($order);
            foreach($resPlaces as $key=>$value){
               $resContact = $this->getContact($value->contact_id);
               $value->{"contact"} = $resContact;
            }

            $this->closeDb();
            return json_encode($resPlaces);
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    public function getPlace($id) {
        try {
            $this->openDb();

            $resPlace = $this->place->selectById($id);
            if( $resPlace )
            {
              $resContact = $this->getContact($resPlace->contact_id);
              $resPlacePlant = $this->getPlacePlant($resPlace->place_id);
              $resPlace->{"contact"} = $resContact;
              $resPlace->{"place_plant"} = $resPlacePlant;
            }
            else{
                return json_encode(array('place_id' => null), JSON_FORCE_OBJECT);
            }
            $this->closeDb();
            return json_encode($resPlace);
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
        return $this->place->find($id);
    }

    public function addPlace( $place_type_id, $place_name, $place_des, $lat, $lng, $phone, $contact_id, $addr, $province_id, $district_id, $order_total, $emp_id ) {
        try {
            $this->openDb();
            $this->validatePlaceParams($place_type_id, $place_name, $place_des, $lat, $lng, $phone, $contact_id, $addr, $province_id, $district_id, $order_total, $emp_id);
            $res = $this->place->insert($place_type_id, $place_name, $place_des, $lat, $lng, $phone, $contact_id, $addr, $province_id, $district_id, $order_total, $emp_id);
            $this->closeDb();
            if( $res)
            {
                return $res;
            }
            else{
                return json_encode(array('place_id' => null), JSON_FORCE_OBJECT);
            }
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    public function updatePlace( $place_id, $place_type_id, $place_name, $place_des, $lat, $lng, $phone, $addr, $province_id, $district_id, $order_total, $emp_id ) {
        try {
            $this->openDb();
            $this->validatePlaceParams($place_type_id, $place_name, $place_des, $lat, $lng, $phone , null, $addr, $province_id, $district_id, $order_total, $emp_id);
            $res = $this->place->update($place_id, $place_type_id, $place_name, $place_des, $lat, $lng, $phone, $addr, $province_id, $district_id, $order_total, $emp_id);
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

    public function deletePlace( $id ) {
        try {
            $this->openDb();
            $res = $this->place->delete($id);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }


}

?>
