<?php

require_once 'model/DbConfig.php';
require_once 'model/Contact.php';
require_once 'model/ValidationException.php';


class ContactService {

    private $contact    = NULL;

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
        $this->contact = new Contact();
    }

    public function getAllContact($order) {
        try {
            $this->openDb();
            $res = $this->contact->selectAll($order);
            $this->closeDb();
            return json_encode($res);
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    public function getContact($id) {
        try {
            $this->openDb();
            $res = $this->contact->selectById($id);
            $this->closeDb();
            if( $res )
            {
              return json_encode($res);
            }
            else{
                return json_encode(array('contact_id' => null), JSON_FORCE_OBJECT);
            }
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
        return $this->contact->find($id);
    }

    public function getContactPost($id) {
        try {
            $this->openDb();
            $res = $this->contact->selectByIdJsonArr($id);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
        return $this->contact->find($id);
    }

    private function validateContactParams( $name, $phone, $email, $line, $fb ) {
        $errors = array();
        if ( !isset($name) || empty($name) ) {
            $errors[] = 'Name is required';
        }
        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }

    public function addContact( $contact_name, $phone, $email, $line, $fb ) {
        try {
            $this->openDb();
            $this->validateContactParams($contact_name, $phone, $email, $line, $fb);
            $res = $this->contact->insert($contact_name, $phone, $email, $line, $fb);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    public function updateContact( $contact_id, $contact_name, $phone, $email, $line, $fb ) {
        try {
            $this->openDb();
            $this->validateContactParams($contact_name, $phone, $email, $line, $fb);
            $res = $this->contact->update($contact_id, $contact_name, $phone, $email, $line, $fb);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    public function deleteContact( $id ) {
        try {
            $this->openDb();
            $res = $this->contact->delete($id);
            $this->closeDb();
            return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }


}

?>
