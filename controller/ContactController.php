<?php

require_once 'model/ContactService.php';

class ContactController {

    private $contactService = NULL;

    public function __construct() {
        $this->contactService = new ContactService();
    }

    public function contactList($data) {
        $orderby = isset($data->order_by) ? $data->order_by :NULL;
        $contacts = $this->contactService->getAllContact($orderby);
        echo $contacts;
    }

    public function contactAdd($data) {
        $title = 'Add new contact';
        $errors = array();
        $contact_name = isset($data->contact_name) ? $data->contact_name :NULL;
        $phone      = isset($data->phone) ?   $data->phone: NULL;
        $email      = isset($data->email) ?   $data->email  :NULL;
        $line       = isset($data->line) ? $data->line :NULL;
        $fb         = isset($data->fb) ? $data->fb :NULL;

        try {
            $res = $this->contactService->addContact($contact_name, $phone, $email, $line, $fb);
            echo $res;
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
        }
    }

    public function contactUpdate($data) {
        $title = 'Update contact';
        $errors = array();

        $contact_id = isset($data->contact_id) ? $data->contact_id :NULL;
        $contact_name = isset($data->contact_name) ? $data->contact_name :NULL;
        $phone      = isset($data->phone) ?   $data->phone: NULL;
        $email      = isset($data->email) ?   $data->email  :NULL;
        $line       = isset($data->line) ? $data->line :NULL;
        $fb         = isset($data->fb) ? $data->fb :NULL;

        try {
            $res = $this->contactService->updateContact($contact_id, $contact_name, $phone, $email, $line, $fb);
            $contact = $this->contactService->getContact($contact_id);

            echo $contact;
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
        }
    }

    public function contactDetail($data) {
        $id = isset($data->contact_id) ? $data->contact_id :NULL;

        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $contact = $this->contactService->getContact($id);
        echo $contact;
    }

    public function contactDelete($data) {
        $id = isset($data->contact_id) ? $data->contact_id :NULL;

        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $this->contactService->deleteContact($id);
    }

}
?>
