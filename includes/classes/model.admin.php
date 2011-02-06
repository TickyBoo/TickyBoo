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
  protected $_columns   = array( 'admin_id', '*admin_login', '*admin_password', '*admin_status', 'admin_email','#admin_user_id',
                                 'admin_ismaster','*admin_inuse');

  static function load ($id = 0){
    $query = "select *
              from Admin
              where admin_id = "._esc($id);
    if ($row = ShopDB::query_one_row($query)){
      $adm = new Admins(false);
      $adm->_fill($row);
      if ($row['admin_status'] =='pos' || $row['admin_status'] =='posman' ) {
        $query = "select *
                  from User
                  where user_id = "._esc($row['admin_user_id']);
        $rowx = ShopDB::query_one_row($query);
      $adm->_fill($rowx);
    }
      return $adm;
    }
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
    if (strpos($data['admin_status'], 'pos') ===0 && empty($data['admin_user_id'])) {
      addError('admin_user_id','mandatory');
    }

    if(!empty($data['admin_email'])){
      if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $data['admin_email'])){
        addError('admin_email','not_valid_email');
      }
    }

    if(!$this->admin_id && empty($data['password1']) ){
        addError('password1','mandatory');
    } elseif(!empty($data['password1']) and strlen($data['password1'])<5){
      addError('password1','pass_too_short');
    } elseif($data['password1']!=$data['password2']){
      addError('password2','pass_not_egal');
    }
    if (!hasErrors() and !empty($data['password1']) ){
      $data['admin_password'] = md5 ($data['password1']);
    } else {
      $data['password1'] = '';
      $data['password2'] = '';
    }
    if(is_array($data['control_event_ids'])){
      $data['control_event_ids'] = implode(',', $data['control_event_ids']);
    }
    return parent::CheckValues($data);
  }

  function delete() {
    if($this->isDeleteSelf() || $this->isLastAdmin()){
      return addWarning('cant_delete_user');;
    }
    if(stripos($this->admin_status,"pos") !== FALSE){
      $query = "SELECT count(*)
                FROM `Order`
                Where order_user_id="._esc($this->user_id);
      //var_dump($res = ShopDB::query_one_row($query, false));
      if (!($res = ShopDB::query_one_row($query, false)) || (int)$res[0]) {
        return addWarning('in_use');
      }

    }
  //  if (parent::delete() and $this->user) {
  //    return $this->user->delete();
   // }
  }

  private function isLastAdmin(){
    if(stripos($this->admin_status,"admin") !== FALSE){
      $query="SELECT COUNT(*) AS admincount
        FROM Admin
        WHERE admin_status='admin'
          AND admin_id <> "._esc((int)$this->admin_id);
      //Any other users apart from you?
      if(!$res=ShopDB::query_one_row($query)){
        user_error(ShopDB::error());
      }elseif($res["admincount"]<1){
        addWarning('last_admin');
        return true;
      }
    }
    return false;
  }

  private function isDeleteSelf(){
    if($_SESSION['_SHOP_AUTH_USER_DATA']['admin_id'] == $this->admin_id){
      addWarning('cant_delete_self');
      return true;
    }
    return false;
  }

  public function isAllowed($Resource, $login = false ) {
  //  print_r($this->admin_status);
    if (plugin::call('%isACL')) {
       return plugin::call('%isAllowedACL', $this->admin_status, $Resource );
    } elseif ($login) { // this ia only used when the ACL manager is not installed.
       return $this->admin_status == $Resource ||
             ($Resource == 'organizer' && $this->admin_status == 'admin') ||
             ($Resource == 'pos' && $this->admin_status == 'posman');
    }
    return true;
}

  public function getEventLinks(){
    global $_SHOP;
    if (!isset($_SHOP->event_ids)) {
      $query="select adminlink_event_id from adminlink
              where adminlink_event_id is not null ";
      if (isset($this->user_id)) {
         $query .= "and adminlink_pos_id = {$this->user_id}";
      } elseif (isset($this->admin_id)) {
         $query .= "and adminlink_admin_id = {$this->admin_id}";
      } else
        return array();
      $list = array();
      if($res=ShopDB::query($query)){
        while($event_d=shopDB::fetch_array($res)){
          $list[]=$event_d[0];
        }
      }
      $_SHOP->event_ids = implode(', ', $list);
    }
    return $_SHOP->event_ids;
  }

  public function getEventRestriction($prefix='', $sefix='AND') {
    $result ='';
    if (($this->admin_status=='organizer' || $this->admin_status=='posman') && ($list=$this->getEventLinks())) {
      $result = "{$sefix} (field({$prefix}event_id, {$list}) or (select  count(*) from `adminlink` where adminlink_event_id = {$prefix}event_id) = 0)";
    }
    return $result;
  }

  static function addResource($Resource) {
    return  plugin::call('%addResourceACL', $Resource );
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
        $query = "select admin_id, admin_password, admin_status
                  from Admin
                  where admin_login = "._esc($username)."
                  and   admin_inuse = 'Yes'";

        $res = ShopDB::query_one_row($query);

        if (!is_array($res)) {
            $this->activeUser = '';
            return false;
        }
        $this->_auth_obj->admin_id = $res['admin_id'];
        // Perform trimming here before the hashihg
        $password = trim($password, "\r\n");
        $res['admin_password'] = trim($res['admin_password'], "\r\n");

        if ($this->verifyPassword($password, $res['admin_password'], $this->cryptType)) {
           $res  = admins::load ($this->_auth_obj->admin_id);
           $this->_auth_obj->admin = $res;
           return $res->isAllowed($this->admin_status, true) ;
        }
//        $this->activeUser = $res[$this->options['usernamecol']];
        return false;
    }

    function getCryptType(){
        return($this->cryptType);
    }

}

?>