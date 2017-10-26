<?php

/**
 * Table data gateway.
 *
 *  OK I'm using old MySQL driver, so kill me ...
 *  This will do for simple apps but for serious apps you should use PDO.
 */
class Contact {

    public function selectAll($order) {
        if ( !isset($order) ) {
            $order = "contact_name";
        }
        $dbOrder =  mysql_real_escape_string($order);
        $dbres = mysql_query("SELECT * FROM contact ORDER BY $dbOrder ASC");
        $contacts = array();
        while ( ($obj = mysql_fetch_object($dbres)) != NULL ) {
            $contacts[] = $obj;
        }
        return $contacts;
    }

    public function selectById($id) {

        $dbId = mysql_real_escape_string($id);
        $dbres = mysql_query("SELECT * FROM contact WHERE contact_id=$dbId");
        $resultArr = mysql_fetch_object($dbres);
        return $resultArr;
    }

    public function insert( $contact_name, $phone, $email, $line, $fb ) {
        $dbName = ($contact_name != NULL)?"'".mysql_real_escape_string($contact_name)."'":'NULL';
        $dbPhone = ($phone != NULL)?"'".mysql_real_escape_string($phone)."'":'NULL';
        $dbEmail = ($email != NULL)?"'".mysql_real_escape_string($email)."'":'NULL';
        $dbLine = ($line != NULL)?"'".mysql_real_escape_string($line)."'":'NULL';
        $dbFb = ($fb != NULL)?"'".mysql_real_escape_string($fb)."'":'NULL';

        $lastInsert = mysql_query("INSERT INTO contact (contact_name, phone, email, line , fb) VALUES ($dbName, $dbPhone, $dbEmail, $dbLine, $dbFb)");
        if($lastInsert){
           return mysql_insert_id();
        } else{
          return json_encode(array('contact_id' => null), JSON_FORCE_OBJECT);
        }
    }

    public function update( $contact_id, $contact_name, $phone, $email, $line, $fb ) {
        $dbId = ($contact_id != NULL)?"'".mysql_real_escape_string($contact_id)."'":'NULL';
        $dbName = ($contact_name != NULL)?"'".mysql_real_escape_string($contact_name)."'":'NULL';
        $dbPhone = ($phone != NULL)?"'".mysql_real_escape_string($phone)."'":'NULL';
        $dbEmail = ($email != NULL)?"'".mysql_real_escape_string($email)."'":'NULL';
        $dbLine = ($line != NULL)?"'".mysql_real_escape_string($line)."'":'NULL';
        $dbFb = ($fb != NULL)?"'".mysql_real_escape_string($fb)."'":'NULL';

        $lastInsert = mysql_query(" UPDATE contact SET contact_name = $dbName, phone = $dbPhone, email = $dbEmail, line = $dbLine, fb = $dbFb WHERE contact_id = $dbId");
        if($lastInsert){
           return $this->selectById($contact_id);
        }else{
          return json_encode(array('contact_id' => null), JSON_FORCE_OBJECT);
        }
    }

    public function delete($id) {
        $dbId = mysql_real_escape_string($id);
        mysql_query("DELETE FROM contact WHERE contact_id=$dbId");
    }

}

?>
