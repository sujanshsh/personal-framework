<?php 

namespace DatabaseOperations\SingleTable;

class Users
{
    
    public static function addUser(&$db,$email,$password) {
        $db->insert_into('users',['email','password'],[$email,$password]);
    }
    
    public static function checkUserEmailExists(&$db,$email) {
        $email_esc = $db->escape_string($email);
        $db->query("select '1' from users where email='$email_esc'");
        return $db->num_rows>0;
    }

    public static function checkUserExists(&$db,$email,$password) {
        $email_esc = $db->escape_string($email);
        $password_esc = $db->escape_string($password);
        $db->query("select '1' from users where email='$email_esc' and password = '$password_esc' ");
        return $db->num_rows>0;
    }

}