<?php

require_once 'model/PlaceService.php';
require_once 'model/PlacePlantService.php';
require_once 'model/ContactService.php';

class PlaceController {

    private $placePlantService = NULL;
    private $placeService = NULL;
    private $contactService = NULL;

    public function __construct() {
      $this->placeService = new PlaceService();
      $this->placePlantService = new PlacePlantService();
      $this->contactService = new ContactService();
    }

    public function placeList($data) {
        $orderby = isset($data->order_by)?$data->order_by:NULL;
        $places = $this->placeService->getAllPlace($orderby);
        echo $places;
    }

    public function placeDetail($data) {
        $id = isset($data->place_id) ? $data->place_id : NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $place = $this->placeService->getPlace($id);
        echo $place;
    }

    public function placeAdd($data) {
        $title = 'Add new place';
        $errors = array();

        $contact_name = isset($data->contact_name) ? $data->contact_name :NULL;
        $phone      = isset($data->phone) ?   $data->phone: NULL;
        $email      = isset($data->email) ?   $data->email  :NULL;
        $line       = isset($data->line) ? $data->line :NULL;
        $fb         = isset($data->fb) ? $data->fb :NULL;

        $place_type_id = isset($data->place_type_id) ?   $data->place_type_id  :NULL;
        $place_name   = isset($data->place_name) ?   $data->place_name  :NULL;
        $place_des    = isset($data->place_des) ?   $data->place_des  :NULL;
        $lat          = isset($data->lat) ?   $data->lat  :NULL;
        $lng          = isset($data->lng) ?   $data->lng  :NULL;
        $phone        = isset($data->phone) ?   $data->phone  :NULL;
        $addr         = isset($data->addr) ?   $data->addr  :NULL;
        $province_id  = isset($data->province_id) ?   $data->province_id  :NULL;
        $district_id  = isset($data->district_id) ?   $data->district_id  :NULL;
        $order_total  = isset($data->order_total) ?   $data->order_total  :NULL;
        $emp_id       = isset($data->emp_id) ?   $data->emp_id  :NULL;

        $placePlantArr       = isset($data->place_plant) ?   $data->place_plant  :NULL;

        try {
            $contactId = $this->contactService->addContact( $contact_name, $phone, $email, $line, $fb );
            $resPlace = $this->placeService->addPlace($place_type_id, $place_name, $place_des, $lat, $lng, $phone, $contactId, $addr, $province_id, $district_id, $order_total, $emp_id);
            if($placePlantArr!=null)
            {
              $resPlacePlant = $this->placePlantService->addPlacePlant( $resPlace,$placePlantArr );
            }
            echo $resPlace;
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
        }
    }

    public function placeUpdate($data) {
        $title = 'Update contact';
        $errors = array();

        // $contact_id = isset($data->contact_id) ?   $data->contact_id  :NULL;
        $contact_name = isset($data->contact_name) ?   $data->contact_name  :NULL;
        $phone      = isset($data->phone)?   $data->phone :NULL;
        $email      = isset($data->email)?   $data->email :NULL;
        $line       = isset($data->line)? $data->line:NULL;
        $fb         = isset($data->fb)? $data->fb:NULL;

        $place_id     = isset($data->place_id) ?   $data->place_id  :NULL;
        $place_type_id= isset($data->place_type_id) ?   $data->place_type_id  :NULL;
        $place_name   = isset($data->place_name) ?   $data->place_name  :NULL;
        $place_des    = isset($data->place_des) ?   $data->place_des  :NULL;
        $lat          = isset($data->lat) ?   $data->lat  :NULL;
        $lng          = isset($data->lng) ?   $data->lng  :NULL;
        $phone        = isset($data->phone) ?   $data->phone  :NULL;
        $contact_id   = isset($data->contact_id) ?   $data->contact_id  :NULL;
        $addr         = isset($data->addr) ?   $data->addr  :NULL;
        $province_id  = isset($data->province_id) ?   $data->province_id  :NULL;
        $district_id  = isset($data->district_id) ?   $data->district_id  :NULL;
        $order_total  = isset($data->order_total) ?   $data->order_total  :NULL;
        $emp_id       = isset($data->emp_id) ?   $data->emp_id  :NULL;

        $placePlantArr       = isset($data->place_plant) ?   $data->place_plant  :NULL;

        try {
            $resPlace = $this->placeService->updatePlace($place_id, $place_type_id, $place_name, $place_des, $lat, $lng, $phone, $addr, $province_id, $district_id, $order_total, $emp_id);
            if( $resPlace ) {
              if($resPlace->contact_id != null) {
                $resContact = $this->contactService->updateContact($resPlace->contact_id, $contact_name, $phone, $email, $line, $fb );
              }
              if($placePlantArr!=null)
              {
                $resplacePlant = $this->placePlantService->addOrUpdatePlacePlant( $placePlantArr );
              }
            } else {
              // contact is null
              // print_r($resPlace);
              // die();
            }
            $place = $this->placeService->getPlace($place_id);
            echo $place;
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
        }
    }

    public function placeDelete($data) {
        $id = isset($data->place_id) ? $data->place_id : NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $this->placeService->deletePlace($id);
        $this->placePlantService->deleteByPlaceId($id);
    }



}
?>
