<?php

/**
 * Table data gateway.
 *
 *  OK I'm using old MySQL driver, so kill me ...
 *  This will do for simple apps but for serious apps you should use PDO.
 */
class Place {

    public function selectAll($order) {
        if ( !isset($order) ) {
            $order = "lat";
        }
        $dbOrder =  mysql_real_escape_string($order);
        $dbres = mysql_query("SELECT * FROM place p left join contact c on (p.contact_id=c.contact_id) LEFT JOIN
    place_type pt ON (p.place_type_id = pt.place_type_id) ORDER BY $dbOrder ASC");
        $places = array();
        while ( ($obj = mysql_fetch_object($dbres)) != NULL ) {
            $places[] = $obj;
        }
        return $places;
    }

    public function selectByMonthYear($month , $year, $orderby) {
        $dbres = mysql_query("SELECT *
    FROM plan pn
        LEFT JOIN
    plan_dt pndt ON (pn.plan_id = pndt.plan_id)
        LEFT JOIN
    place pc ON (pndt.place_id = pc.place_id)
        LEFT JOIN
    contact ct ON (pc.contact_id = ct.contact_id)
        LEFT JOIN
    place_type pt ON (pc.place_type_id = pt.place_type_id)
        LEFT JOIN
    fix_type ft ON (pndt.fix_type_id = ft.fix_type_id)
    where month = $month and year = $year order by $orderby asc");
        $places = array();
        while ( ($obj = mysql_fetch_object($dbres)) != NULL ) {
            $places[] = $obj;
        }
        return $places;
    }

    public function allPlaceMonthYear($month, $year, $orderby) {
        $dbres = mysql_query("SELECT pc.place_id,
            pc.place_name,
            pc.place_type_id,
            pc.lat,
            pc.lng,
            pc.total_sales,
            GROUP_CONCAT(DISTINCT (plnt.plant_name)
                SEPARATOR ',') as plant_name_arr,
            sum(plntmnth.age_level) as is_hot
        FROM
            place pc
                LEFT JOIN
            place_type pt ON (pc.place_type_id = pt.place_type_id)
                LEFT JOIN
            place_plant pcplnt ON (pc.place_id = pcplnt.place_id )
                LEFT JOIN
            plant plnt ON (plnt.plant_id = pcplnt.plant_id)
                LEFT JOIN
            plant_month plntmnth ON (plnt.plant_id = plntmnth.plant_id and plntmnth.month_num = $month )

        WHERE
        pc.place_id NOT IN (SELECT DISTINCT
                (pndt.place_id)
            FROM
                plan pn
                    LEFT JOIN
                plan_dt pndt ON (pn.plan_id = pndt.plan_id)
            WHERE
                pn.month = $month
                AND pn.year = $year)
      --  AND plan_dt_id IS NOT null
        GROUP BY pc.place_id ");
        $places = array();
        while ( ($obj = mysql_fetch_object($dbres)) != NULL ) {
            $places[] = $obj;
        }
        return $places;
    }

    public function sumByMonthYear($month , $year, $orderby) {
        $dbres = mysql_query("SELECT
        pc.place_id,
        pn.month,
        pn.year,
        GROUP_CONCAT(DISTINCT (pndt.seq)
            SEPARATOR ',') as seq_arr,
        pc.place_name,
        pc.is_dealer,
        pc.lat,
        pc.lng
    -- ,
      --  pc.total_sales,
      --  GROUP_CONCAT(DISTINCT (plnt.plant_name)
      --      SEPARATOR ',') as plant_name_arr,
      --  plntmnth.month_num,
      --  sum(plntmnth.age_level) as is_hot
    FROM
        plan pn
            LEFT JOIN
        plan_dt pndt ON (pn.plan_id = pndt.plan_id)
            LEFT JOIN
        place pc ON (pndt.place_id = pc.place_id)
            LEFT JOIN
        contact ct ON (pc.contact_id = ct.contact_id)
            LEFT JOIN
        place_type pt ON (pc.place_type_id = pt.place_type_id)
            LEFT JOIN
        fix_type ft ON (pndt.fix_type_id = ft.fix_type_id)
            LEFT JOIN
        place_plant pcplnt ON (pc.place_id = pcplnt.place_id)
   --         LEFT JOIN
   --     plant plnt ON (plnt.plant_id = pcplnt.plant_id)
   --         LEFT JOIN
     --   plant_month plntmnth ON (plnt.plant_id = plntmnth.plant_id and plntmnth.month_num = 3 )
    WHERE
        pn.month = 3
          --  AND plntmnth.month_num = $month
            AND pn.year = 2018
          --  AND plntmnth.age_level = 1
    GROUP BY pc.place_id
 --   ORDER BY  pc.total_sales DESC");
        $places = array();
        while ( ($obj = mysql_fetch_object($dbres)) != NULL ) {
            $places[] = $obj;
        }
        return $places;
    }

    public function selectById($id) {
        $dbId = mysql_real_escape_string($id);
        $dbres = mysql_query("SELECT * FROM place p left join contact c on (p.contact_id=c.contact_id) LEFT JOIN
    place_type pt ON (p.place_type_id = pt.place_type_id) where place_id=$dbId");
        $result = mysql_fetch_object($dbres);
        if($result){
           return $result;
        }else{
          return false;
        }
    }

    public function insert( $place_type_id, $place_name, $place_des, $lat, $lng, $phone, $contact_id, $addr, $province_id, $district_id, $order_total, $emp_id ) {
        // $place_id
        $dbPlaceTypeId = ($place_type_id != NULL)?"'".mysql_real_escape_string($place_type_id)."'":'NULL';
        $dbPlaceName = ($place_name != NULL)?"'".mysql_real_escape_string($place_name)."'":'NULL';
        $dbPlaceDes = ($place_des != NULL)?"'".mysql_real_escape_string($place_des)."'":'NULL';
        $dbLat = ($lat != NULL)?"'".mysql_real_escape_string($lat)."'":'NULL';
        $dbLng = ($lng != NULL)?"'".mysql_real_escape_string($lng)."'":'NULL';
        $dbPhone = ($phone != NULL)?"'".mysql_real_escape_string($phone)."'":'NULL';
        $dbContactId = ($contact_id != NULL)?"'".mysql_real_escape_string($contact_id)."'":'NULL';
        $dbAddr = ($addr != NULL)?"'".mysql_real_escape_string($addr)."'":'NULL';
        $dbProvinceId = ($province_id != NULL)?"'".mysql_real_escape_string($province_id)."'":'NULL';
        $dbDistrictId = ($district_id != NULL)?"'".mysql_real_escape_string($district_id)."'":'NULL';
        $dbOrderTotal = ($order_total != NULL)?"'".mysql_real_escape_string($order_total)."'":'NULL';
        $dbEmpId = ($emp_id != NULL)?"'".mysql_real_escape_string($emp_id)."'":'NULL';

        $lastQuery = mysql_query("INSERT INTO place (place_type_id, place_name, place_des, lat, lng, phone, contact_id, addr, province_id, district_id, order_total, emp_id) VALUES ($dbPlaceTypeId, $dbPlaceName, $dbPlaceDes, $dbLat, $dbLng, $dbPhone, $dbContactId, $dbAddr, $dbProvinceId, $dbDistrictId, $dbOrderTotal, $dbEmpId)");
        if($lastQuery){
           return mysql_insert_id();
        }else{
          return false;
        }
    }

    public function update( $place_id, $place_type_id, $place_name, $place_des, $lat, $lng, $phone,  $addr, $province_id, $district_id, $order_total, $emp_id ) {
        $dbId = ($place_id != NULL)?"'".mysql_real_escape_string($place_id)."'":'NULL';
        $dbPlaceTypeId = ($place_type_id != NULL)?"'".mysql_real_escape_string($place_type_id)."'":'NULL';
        $dbPlaceName = ($place_name != NULL)?"'".mysql_real_escape_string($place_name)."'":'NULL';
        $dbPlaceDes = ($place_des != NULL)?"'".mysql_real_escape_string($place_des)."'":'NULL';
        $dbLat = ($lat != NULL)?"'".mysql_real_escape_string($lat)."'":'NULL';
        $dbLng = ($lng != NULL)?"'".mysql_real_escape_string($lng)."'":'NULL';
        $dbPhone = ($phone != NULL)?"'".mysql_real_escape_string($phone)."'":'NULL';
        // $dbContactId = ($contact_id != NULL)?"'".mysql_real_escape_string($contact_id)."'":'NULL';
        $dbAddr = ($addr != NULL)?"'".mysql_real_escape_string($addr)."'":'NULL';
        $dbProvinceId = ($province_id != NULL)?"'".mysql_real_escape_string($province_id)."'":'NULL';
        $dbDistrictId = ($district_id != NULL)?"'".mysql_real_escape_string($district_id)."'":'NULL';
        $dbOrderTotal = ($order_total != NULL)?"'".mysql_real_escape_string($order_total)."'":'NULL';
        $dbEmpId = ($emp_id != NULL)?"'".mysql_real_escape_string($emp_id)."'":'NULL';

        $lastQuery = mysql_query(" UPDATE place SET place_type_id = $dbPlaceTypeId, place_name = $dbPlaceName, place_des = $dbPlaceDes, lat = $dbLat, lng = $dbLng, phone = $dbPhone,  addr = $dbAddr, province_id = $dbProvinceId, district_id = $dbDistrictId, order_total = $dbOrderTotal, emp_id = $dbEmpId WHERE place_id = $dbId");
        if($lastQuery){
           return $this->selectById($place_id);
        }else{
          return false;
        }
    }

    public function delete($id) {
        $dbId = mysql_real_escape_string($id);
        mysql_query("DELETE FROM place WHERE place_id=$dbId");
    }

}

?>
