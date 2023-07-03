<?php

function input($id){
    $value = isset($_POST[$id]) ? $_POST[$id] : '';
    return "<input type='text' id='$id' name='$id' value='$value'>";
};

function textarea($id){
    $value = isset($_POST[$id]) ? $_POST[$id] : '';
    return "<textarea type='text' id='$id' name='$id'>$value</textarea>";
};

function select($id, $options = array()){
    $return = "<select id='$id' name='$id'>";
    foreach($options as $k => $v){
        $selected = '';
        if(isset($_POST[$id]) && $k == $_POST[$id]){
            $select = 'selected="selected"';
        }
        $return .= "<option value='$k' $selected>$v</option>";
    }
    $return .= '</select>';
    return $return;
}

?>