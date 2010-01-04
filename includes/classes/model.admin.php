<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
include_once 'Auth/Container.php';

class CustomAuthContainer extends Auth_Container {
    /**
     * Constructor
     */
    Private $admin_status;
    var $cryptType = 'md5';

    function CustomAuthContainer($params) {
      $this->admin_status = $params;
    }

    function supportsChallengeResponse() {
      return true;
    }

    function fetchData($username, $password) {
        // Check If valid etc
        $query = "select admin_password
                  from Admin
                  where admin_login = "._esc($username)."
                  and   (admin_status like '{$this->admin_status}' or
                         admin_status like 'admin')";
        $res = ShopDB::query_one_row($query);

        if (!is_array($res)) {
            $this->activeUser = '';
            return false;
        }

        // Perform trimming here before the hashihg
        $password = trim($password, "\r\n");
        $res['admin_password'] = trim($res['admin_password'], "\r\n");

        // If using Challenge Response md5 the pass with the secret
        if ($isChallengeResponse) {
            $res['admin_password'] = md5($res['admin_password'].$this->_auth_obj->session['loginchallenege']);

            // UGLY cannot avoid without modifying verifyPassword
            $res['admin_password'] = md5($res['admin_password']);

            //print " Hashed Password [{$res[$this->options['passwordcol']]}]<br/>\n";
        }

   //     var_dump( $res['admin_password']);
    //    var_dump(md5($password));

        if ($this->verifyPassword($password, $res['admin_password'], $this->cryptType)) {
           return true;
        }
//        $this->activeUser = $res[$this->options['usernamecol']];
        return false;
    }

    function getCryptType(){
        return($this->cryptType);
    }

}

?>