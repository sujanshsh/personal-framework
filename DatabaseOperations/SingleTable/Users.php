<?php 

namespace DatabaseOperations\SingleTable;

class Users
{
    
    public static function addUser(DatabaseInterface &$db,$email,$password) {
        $email_esc = $db->escape_string($email);
        $password_esc = $db->escape_string($password);
        $db->execute("insert into users (email,password) values ('$email_esc','$password_esc')");
    }
    
    public static function checkUserEmailExists(DatabaseInterface &$db,$email) {
        $email_esc = $db->escape_string($email);
        return $db->execute("select '1' from users where email='$email_esc'")->num_rows() > 0;
    }

    public static function checkUserExists(DatabaseInterface &$db,$email,$password) {
        $email_esc = $db->escape_string($email);
        $password_esc = $db->escape_string($password);
        return $db->execute("select '1' from users where email='$email_esc' and password = '$password_esc' ")->num_rows() > 0;
    }

}