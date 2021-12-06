<?php
class queryCreator{

    public function getValues($condition){
        $resourcesLength = count($_GET);
        $where = "WHERE ";
        if($condition == "and"){
            foreach($_GET as $key => $value){
                if($resourcesLength > 1){
                    $where .= "$key = $value AND ";
                    $resourcesLength--;
                }else{
                    return $where .= "$key = $value";
                }
            }
        }
    }

    public function setValues($responseParameters){
        $resourcesLength = count($responseParameters);
        $columns = "(";
        $values = "VALUES (";
        foreach($responseParameters as $key => $value){
            if($resourcesLength > 1){
                $columns .= "$key,";
                $values .= "'$value',";
                $resourcesLength--;
            }else{
                $columns .= "$key) ";
                $values .= "'$value')";
                return $columns . $values;
            }
        }
    }

    public function updateValues($responseParameters){
        $resourcesLength = count($responseParameters);
        $set = "SET ";
        foreach($responseParameters as $key => $value){
            if($resourcesLength > 1){
                $set .= "$key = '$value',";
                $resourcesLength--;
            }else{
                $set .= "$key = '$value' ";
                return $set ;    
            }
        }
    }



}
?>