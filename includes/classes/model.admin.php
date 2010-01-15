<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
include_once 'Auth/Container.php';
class Admins extends Model {
  protected $_idName    = 'admin_id';
  protected $_tableName = 'Admin';
  protected $_columns   = array( 'admin_id', '*admin_login', '*admin_password', 'admin_status',
                                 '#admin_user_id', 'admin_ismaster','*admin_inuse','control_event_ids');

  function __construct($filldefs= false, $admintype='') {
    parent::__construct($filldefs);
    if ( $admintype=='pos') {
      $this->user = new User($filldefs);
    }
    $this->admin_status =$admintype;
  }

  function load ($id = 0){
    $query = "select *
              from Admin
              where admin_id = "._esc($id);
    if ($row = ShopDB::query_one_row($query)){
      $adm = new Admins(false, $row['admin_status']);
      if ($adm->admin_status =='pos') {
        $query = "select *
                  from User
                  where user_id = "._esc($row['admin_user_id']);
        $rowx = ShopDB::query_one_row($query);
      } else $rowx = array();
      $adm->_fill($row);
      $adm->_fill($rowx);
      return $adm;
    }
  }

  function saveEx() {
    if ($this->admin_status =='pos') {
      $this->admin_user_id = $this->user->saveEx();
    }
    if (($this->admin_status !=='pos') || ($this->admin_user_id)) {
      return parent::saveEx();
    }
    return false;
  }

  function CheckValues(&$data) {
    $nickname=$data['admin_login'];
    if(empty($data['admin_login'])){
      addError('admin_login','mandatory');
    } else {
      $query="select Count(*) as count
              from Admin
              where admin_login= "._esc($nickname)."
              and admin_id <> "._esc((int)$this->admin_id);
      if(!$res=ShopDB::query_one_row($query)){
        user_error(shopDB::error());
      } elseif($res["count"]>0){
        addError('admin_login','already_exist');
      }
    }
    if(!$this->admin_id){
      if(empty($data['password1']) ){
        addError('password1','mandatory');
      } elseif(empty($data['password2'])){
        addError('password2','mandatory');
      }
    } elseif(!empty($data['password1']) and strlen($data['password1'])<5){
      addError('password1','pass_too_short');
    } elseif($data['password1']!=$data['password2']){
      addError('password2','pass_not_egal');
    }
    if (!hasErrors() and !empty($data['password1']) ){
      $data['admin_password'] = md5 ($data['password1']);
    }
    if(is_array($data['control_event_ids'])){
      $data['control_event_ids'] = implode(',', $data['control_event_ids']);
    }
    $data['user_lastname'] = $data['kasse_name'];
    $data['user_firstname'] = 'POS:';
    return parent::CheckValues($data);
  }

  function _fill($arr , $nocheck=true)  {
    if (parent::_fill($arr , $nocheck)){
      return (!$this->user) || $this->user->_fill($arr , $nocheck);
    } else
      return false;
  }

  function delete() {
    if (parent::delete() and $this->user) {
      return $this->user->delete();
    }
  }
}

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
                  and   (admin_status like '{$this->admin_status}'";
        if ($this->admin_status == 'organizer'){
          $query .= " or admin_status like 'admin')";
        }
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