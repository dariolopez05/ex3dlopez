<?php

class Lighting extends Connection{

    public function __construct()
    {
        parent::__construct();
    }

    function importLamps($file){
        $conn= $this->getConn();
        $query = "DELETE FROM `lamps`";
        $result = mysqli_query($conn, $query);

        $gestor = fopen($file, "r");
        $query = "INSERT INTO `lamps`(`lamp_id`, `lamp_name`, `lamp_model`, `lamp_zone`, `lamp_on`) VALUES (?,?,?,?,?)";
        
        while (($element = fgetcsv($gestor)) !== false) {
            $id = $element[0];
            $name = $element[1];
            $model = $element[2];
            $ubication = $element[3];
            $state = $element[4];

            $modelId = $this->getModelId($model);
            $zoneId = $this->getZonelId($ubication);
            $stateName = $this->getState($state);

            $ready = $conn->prepare($query);
            $ready->bind_param("sssss", $id, $name, $modelId, $zoneId, $stateName);
            $ready->execute();
            $result = $ready->get_result();
            $ready->close();
        }
        fclose($gestor);
    }

    function getModelId($name){
        $conn= $this->getConn();
        $query = "SELECT `model_id` FROM `lamp_models` WHERE `model_part_number` = ?";
        $ready = $conn->prepare($query);
        $ready->bind_param("s", $name);
        $ready->execute();
        $result = $ready->get_result();
        $id = $result->fetch_array(MYSQLI_ASSOC);
        $ready->close();
        return $id["model_id"];
    }

    function getZonelId($zone){
        $conn= $this->getConn();
        $query = "SELECT `zone_id` FROM `zones` WHERE `zone_name` = ?";
        $ready = $conn->prepare($query);
        $ready->bind_param("s", $zone);
        $ready->execute();
        $result = $ready->get_result();
        $id = $result->fetch_array(MYSQLI_ASSOC);
        $ready->close();
        return $id["zone_id"];
    }

    function getState($state){
        if ($state == "off") {
            $stateName = 0;
        } else {
            $stateName = 1;
        }
        return $stateName;
    }

    function getAllLamps(){
        $array = [];
        $conn= $this->getConn();
        $query = "SELECT * FROM `lamps`";
        $result = mysqli_query($conn, $query);
        $total = $result->num_rows;
        $cont = 0;
        while ($cont < $total) {
            $result->data_seek($cont);
            $info = $result->fetch_array(MYSQLI_ASSOC);
            $id = $info["lamp_id"];
            $name = $info["lamp_name"];
            $state = $info["lamp_on"];
            $model = $info["lamp_model"];
            $power = $this->getModelVolt($model);
            $ubication = $info["lamp_zone"];

            $object = new Lamp($id, $name, $state, $model, $power, $ubication);

            array_push($array, $object);
            $cont++;
        }
        return($array);
    }

    function drawZonesOptions($num){
        $array = $this->getAllLamps();
        $newArray = [];

        if ($num == 1) {
            foreach ($array as $element) {
                $zone = $element->getUbication();
                if ($zone == 1) {
                    array_push($newArray, $element);
                }
            }
        } elseif ($num == 2) {
            foreach ($array as $element) {
                $zone = $element->getUbication();
                if ($zone == 2) {
                    array_push($newArray, $element);
                }
            }
        } elseif ($num == 3) {
            foreach ($array as $element) {
                $zone = $element->getUbication();
                if ($zone == 3) {
                    array_push($newArray, $element);
                }
            }
        } elseif ($num == 4) {
            foreach ($array as $element) {
                $zone = $element->getUbication();
                if ($zone == 4) {
                    array_push($newArray, $element);
                }
            }
        } else {        
            $newArray = $array;
        }
        $output = $this->drawLampsList($newArray);
        return $output;
    }

    function drawLampsList($array) {
        $output = "";
        foreach ($array as $element) {
            $id = $element->getId();
            $name = $element->getName();
            $zoneId = $element->getUbication();
            $volt = $element->getVatios();
            $state = $element->getState();
            $zone = $this->getZoneName($zoneId);
            if ($state == 0) {
                $output .= "<div class='element off'>";
            } else {
                $output .= "<div class='element on'>";

            }
            $output .= "<h4><a href='changestatus.php?id=$id&status=$state'><img src='img/bulb-icon-off.png'></a> $name </h4>";
            $output .= "<h1> $volt </h1>";
            $output .= "<h4> $zone </h4>";
            $output .= "</div>";
        }
        return $output;
    }

    function getModelVolt($id){
        $conn= $this->getConn();
        $query = "SELECT `model_wattage` FROM `lamp_models` WHERE `model_id` = ?";
        $ready = $conn->prepare($query);
        $ready->bind_param("s", $id);
        $ready->execute();
        $result = $ready->get_result();
        $id = $result->fetch_array(MYSQLI_ASSOC);
        $ready->close();
        return $id["model_wattage"];
    }

    function getZoneName($zoneId){
        $conn= $this->getConn();
        $query = "SELECT `zone_name` FROM `zones` WHERE `zone_id` = ?";
        $ready = $conn->prepare($query);
        $ready->bind_param("s", $zoneId);
        $ready->execute();
        $result = $ready->get_result();
        $id = $result->fetch_array(MYSQLI_ASSOC);
        $ready->close();
        return $id["zone_name"];
    }

    function getPowerZone() {
        $array = $this->getAllLamps();
        $output = "";
        $zone1 = 0;
        $zone2 = 0; 
        $zone3 = 0; 
        $zone4 = 0; 

        foreach ($array as $element) {
            $zone = $element->getUbication();
            $power = $element->getVatios();
            if ($zone == 1) {
                $zone1 += $power;
            } elseif ($zone == 2) {
                $zone2 += $power;
            } elseif ($zone == 3) {
                $zone3 += $power;
            } elseif ($zone == 4) {
                $zone4 += $power;
            } 
        }
        $output .="<h1 id='color'> Fondo Norte: $zone1 <br> Fondo Sur: $zone2 <br> Grada Este: $zone3 <br> Grada Oeste: $zone4 </h1>";
        return $output;
    }

    function changeState($id, $status){
        $conn= $this->getConn();
        if ($status == 0) {
            $query = "UPDATE `lamps` SET `lamp_on`= 1 WHERE `lamp_id` = $id";
            $result = mysqli_query($conn, $query);
        } else {
            $query = "UPDATE `lamps` SET `lamp_on`= 0 WHERE `lamp_id` = $id";
            $result = mysqli_query($conn, $query);
        }
    }

}

?>