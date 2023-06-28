<?php

function input($id){
    $value = isset($_POST[$id]) ? $_POST[$id] : "";
    return  "<input type='text' id='$id' name='username' value='" . $value . "'>";
};

?>