<?php

require_once 'ContactController.php';
require_once 'PlaceController.php';

class Controller {

  private $contactController = NULL;
    private $placeController = NULL;

    public function __construct() {
      $this->contactController = new ContactController();
      $this->placeController = new PlaceController();
    }

    public function redirect($location) {
        header('Location: '.$location);
    }

    public function handleRequest() {


        $postReq = json_decode(file_get_contents('php://input'));
        $apiName = isset($postReq->api_name) ? $postReq->api_name : NULL;
        $apiMethod = isset($postReq->api_method) ? $postReq->api_method : NULL;
        $data = isset($postReq->data) ? $postReq->data : NULL;
        // echo $apiName;
        // echo $apiMethod;
        // echo $postReq->data->data_1;
        // echo $postReq->data->data_2[0]->data_22;

        // echo
        try {
            if ( $apiName == 'contact' )
            {
              switch ($apiMethod) {
              case 'list':
                    $this->contactController->contactList($data);
                    break;
              case 'detail':
                    $this->contactController->contactDetail($data);
                    break;
              case 'add':
                    $this->contactController->contactAdd($data);
                    break;
              case 'update':
                    $this->contactController->contactUpdate($data);
                    break;
              case 'delete':
                    $this->contactController->contactDelete($data);
                    break;
              default:
                    break;
                  }
            }
            elseif( $apiName == 'place' )
            {
              switch ($apiMethod) {
              case 'allmy':
                    $this->placeController->placeAllMonthYear($data);
                    break;
              case 'list':
                    $this->placeController->placeList($data);
                    break;
              case 'detail':
                    $this->placeController->placeDetail($data);
                    break;
              case 'add':
                    $this->placeController->placeAdd($data);
                    break;
              case 'update':
                    $this->placeController->placeUpdate($data);
                    break;
              case 'delete':
                    $this->placeController->placeDelete($data);
                    break;
              default:
                    break;
              }
            }
            elseif( $apiName == 'plan' )
            {
              switch ($apiMethod) {
              case 'list':
                    $this->placeController->planList($data);
                    break;
              case 'sum':
                    $this->placeController->planSum($data);
                    break;
              case 'detail':
                    $this->placeController->placeDetail($data);
                    break;
              case 'add':
                    $this->placeController->placeAdd($data);
                    break;
              case 'update':
                    $this->placeController->placeUpdate($data);
                    break;
              case 'delete':
                    $this->placeController->placeDelete($data);
                    break;
              default:
                    break;
              }
            }
            else {
                $this->showError("API not found", "Page for operation ".$apiMethod." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        }
    }

}
?>
