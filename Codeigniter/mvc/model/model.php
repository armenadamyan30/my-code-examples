<?php

include_once 'model/db.php';

class Model {

    public $appId;

    public function __construct() {
        $db = new DB();
    }

    public function get_inputs($form, $data_id) {
        $form = mysql_real_escape_string($form);
        $data_id = mysql_real_escape_string($data_id);
        $query = "SELECT * "
            . "FROM $form "
            . "WHERE data_id='" . $data_id . "'";
        $result = mysql_query($query);
        $data_array = array();
        if ($result && mysql_num_rows($result) == 1) {
            $row = mysql_fetch_array($result);
            $data_array = json_decode($row['data'], true);
        }
        mysql_free_result($result);
        return $data_array;
    }

    public function is_data_exists($form, $data_id) {
        $form = mysql_real_escape_string($form);
        $data_id = mysql_real_escape_string($data_id);
        $query = "SELECT data_id FROM $form WHERE data_id='$data_id'";
        $result = mysql_query($query);
        if ($result) {
            return mysql_num_rows($result) == 1;
        }
        mysql_free_result($result);
        return $result;
    }

    public function update_form_table($form, $json_data, $app_label, $data_id, $filled = 1) {
        $form = mysql_real_escape_string($form);
        $app_label = mysql_real_escape_string($app_label);
        $data_id = mysql_real_escape_string($data_id);
        $filled = mysql_real_escape_string($filled);
        $query = "UPDATE $form "
            . "SET data='$json_data', app_label='$app_label', filled='$filled'"
            . "WHERE data_id='" . $data_id . "'";
        mysql_query($query);
        mysql_free_result($result);
    }

    public function insert_form_table($form, $json_data, $app_label, $data_id, $filled = 1) {
        $form = mysql_real_escape_string($form);
        $app_label = mysql_real_escape_string($app_label);
        $data_id = mysql_real_escape_string($data_id);
        $filled = mysql_real_escape_string($filled);
        $query = "INSERT INTO $form "
            . "(app_label, data_id, data, filled) "
            . "VALUES ('$app_label','$data_id','$json_data', '$filled')";
        mysql_query($query);
        mysql_free_result($result);
    }

    function getCurrentFile($appId, $table = 'drivers') {
        $appId = mysql_real_escape_string($appId);
        $table = mysql_real_escape_string($table);
        $query = "SELECT * FROM $table WHERE data_id='$appId'";
        $result = mysql_query($query);
        $fileName = false;
        if ($row = mysql_fetch_assoc($result)) {
            $fileName = $row["filename"];
        }
        mysql_free_result($result);
        return $fileName;
    }

    function updateFileTable($appId, $img_name, $prevFileNmae, $table = 'drivers') {
        $appId = mysql_real_escape_string($appId);
        $img_name = mysql_real_escape_string($img_name);
        $prevFileNmae = mysql_real_escape_string($prevFileNmae);
        $table = mysql_real_escape_string($table);
        $dateAdded = date("Y-m-d H:i:s");
        if ($prevFileNmae) {
            $query = "UPDATE $table "
                . "SET filename='$img_name', date='$dateAdded'"
                . "WHERE data_id='$appId'";
        } else {
            $query = "INSERT INTO $table (data_id, filename, date)"
                . "VALUES ('$appId','$img_name','$dateAdded')";
        }
        $result = mysql_query($query);
        mysql_free_result($result);
    }

    function check_form_state($form, $appId) {
        $form = mysql_real_escape_string($form);
        $appId = mysql_real_escape_string($appId);
        $query = "SELECT * FROM $form "
            . " WHERE data_id='$appId' && filled='1'";
        $result = mysql_query($query);
        if ($result) {
            return mysql_num_rows($result) == 1;
        }
        mysql_free_result($result);
        return $result;
    }

    function check_application_id($appId) {
        $appId = mysql_real_escape_string($appId);
        $query = <<<SQL
SELECT *
FROM applications
WHERE data_id = "$appId"
LIMIT 0,1

SQL;
        $result = mysql_query($query);
        if ($result)
            $row = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $row;
    }

    function insert_application_id($appId) {
        $appId = mysql_real_escape_string($appId);
        $query = <<<SQL
INSERT INTO applications (data_id, read_only, date_added, date_modified)
VALUES ('$appId', 0, now(), now())

SQL;
        $result = mysql_query($query);
        if ($result) {
            return mysql_num_rows($result) == 1;
        }
        mysql_free_result($result);
        return $result;
    }

    function make_application_id_read_only($appId) {
        $appId = mysql_real_escape_string($appId);

        $query = <<<SQL
UPDATE applications
SET read_only = 1,
date_modified = now()
WHERE data_id = '$appId'

SQL;
        $result = mysql_query($query);
        if ($result) {
            return mysql_num_rows($result) == 1;
        }
        mysql_free_result($result);
        return $result;
    }

    function updade_other_forms_appropriate_fields($curr_form, $data_array, $data_id) {
        $forms = unserialize(FORMS);
//        unset($forms[$curr_form], $forms["drivers"]); //drivers table for loaded images
        foreach ($forms as $form) {
            if ($this->is_data_exists($form, $data_id)) {
                $this->update_form_common_fields($form, $data_array, $data_id);
            } else {
                $this->insert_form_common_fields($form, $data_array, $data_id);
            }
        }
    }

    function update_form_common_fields($form, $data_array, $data_id) {
        $common_fields = unserialize(FORMS_COMMON_FIELDS);
        $form_val = $this->get_inputs($form, $data_id);
        foreach ($common_fields as $field) {
            if (isset($data_array[$field])) {
                $form_val[$field] = $data_array[$field];
            }
        }
        $json_data = json_encode($form_val);
        $form = mysql_real_escape_string($form);
        $data_id = mysql_real_escape_string($data_id);
        $query = "UPDATE $form "
            . "SET data='$json_data'"
            . "WHERE data_id='" . $data_id . "'";
        mysql_query($query);
        mysql_free_result($result);
    }

    function insert_form_common_fields($form, $data_array, $data_id) {
        $common_fields = unserialize(FORMS_COMMON_FIELDS);
        $form_val = array();
        foreach ($common_fields as $field) {
            if (isset($data_array[$field])) {
                $form_val[$field] = $data_array[$field];
            }
        }
        $json_data = json_encode($form_val);
        $form = mysql_real_escape_string($form);
        $data_id = mysql_real_escape_string($data_id);
        $query = "INSERT INTO $form "
            . "(data_id, data) "
            . "VALUES ('$data_id','$json_data')";
        mysql_query($query);
        mysql_free_result($result);
    }

}
