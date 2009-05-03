<?php


class Awf_Acl {

    /**
     * Hier worden de roles (rollen) opgeslagen
     *
     * @var array
     */
    protected $roles = array();

    /**
     * Opslag van de resources
     *
     * @var array
     */
    protected $resources = array();

    /**
     * Opslag van alle rules (Regels, wat mag een role wel en wat niet)
     *
     * @var array
     */
    protected $rules = array();

    /**
     * Snelmethod om een role of resource toe te voegen
     *
     * @param Awf_Acl_Role|Awf_Acl_Resource $instance
     * @return Awf_Acl instance van zichzelf voor chaining
     */
    public function add($instance){
        if($instance instanceof Awf_Acl_Role){
            return $this->addRole($instance);
        }elseif($instance instanceof Awf_Acl_Resource){
            return $this->addResource($instance);
        }
        throw new Exception('This is not a valid instance to add to Awf_Acl!');
    }

    /**
     * Voeg een role toe
     *
     * @param Awf_Acl_Role $role Instantie van een role
     * @return Awf_Acl instance van zichzelf voor chaining
     */
    public function addRole(Awf_Acl_Role $role){
        $this->roles[(string)$role] = $role;
        return $this;
    }

    /**
     * Voeg een resource toe
     *
     * @param Awf_Acl_Resource $resource instantie van een resource
     * @return Awf_Acl instance van zichzelf voor chaining
     */
    public function addResource(Awf_Acl_Resource $resource){
        $this->resources[(string)$resource] = $resource;
        return $this;
    }

    /**
     * Haal een role uit de $this->roles array
     *
     * @param string $role
     * @return Awf_Acl_Role
     */
    protected function getRole($role){
        if($role instanceof Awf_Acl_Role){
            return $role;
        }
        if(isset($this->roles[(string)$role])){
            $role = $this->roles[(string)$role];
            if($role instanceof Awf_Acl_Role){
                return $role;
            }
        }
        throw new Exception('This role ('.$role.') does not exists!');
    }

    /**
     * Haal een resource uit $this->resources
     *
     * @param string $resource
     * @return Awf_Acl_Resource
     */
    protected function getResource($resource){
        if($resource instanceof Awf_Acl_Resource){
            return $resource;
        }
        if(isset($this->resources[(string)$resource])){
            $resource = $this->resources[(string)$resource];
            if($resource instanceof Awf_Acl_Resource){
                return $resource;
            }
        }
        throw new Exception('This resource ('.$resource.') does not exists!');
    }

    protected function getRule(Awf_Acl_Role $role){
        $roleName = (string)$role;
        if(isset($this->rules[$roleName])){
            return $this->rules[$roleName];
        }
        return 0;
    }

    protected function setRule(Awf_Acl_Role $role,$value){
        $roleName = (string)$role;
        $this->rules[$roleName] = $value;
    }

    /**
     * Voegt een resource toe aan de 'rules' lijst
     * Voegt de bits samen: 0100 + 1010 => 1110
     *
     * @param string $role
     * @param string $resource
     *
     * @return Awf_Acl om chaining mogelijk te maken
     */
    public function allow($role,$resource){
        $role = $this->getRole($role);
        $resource = $this->getResource($resource);

        $this->setRule($role,$this->getRule($role) | $resource->getBit());
        return $this;
    }

    /**
     * Alle rechten die de $extend heeft, worden ook aan de $role gegeven
     *
     * @param string $role
     * @param string $extendRole De rechten die moeten worden overgenomen
     * @return Awf_Acl om chaining mogelijk te maken
     */
    public function extend($role,$extendRole){
        $role = $this->getRole($role);
        $extendRole = $this->getRole($extendRole);

        $this->setRule($role,$this->getRule($role) | $this->getRule($extendRole));
        return $this;

    }

    /**
     * Haalt een resource weer uit de rules lijst.
     * Trekt de bits uit elkaar: 1101 - 1011 => 0100
     * @todo Is hier geen beter oplossing voor dan deze loop ??
     *
     * @param string $role
     * @param string $resource
     * @return Awf_Acl om chaining mogelijk te maken
     */
    public function deny($role,$resource){
        $role = $this->getRole($role);
        $resource = $this->getResource($resource);

        // De rule moet wel al bestaan, anders kan er niks af worden gehaald!
        if(!$this->getRule($role) === 0){
            return $this;
        }

        $allow = decbin($this->getRule($role));
        $length = strlen($allow);
        $resBits = decbin($resource->getBit());
        $resLength = strlen($resBits);
        for($i=1;$i<=$length;$i++){
            if(isset($resBits{$resLength-$i}) and $allow{$length-$i} == $resBits{$resLength-$i}){
                $allow{$length-$i} = 0;
            }
        }
        $this->setRule($role,bindec($allow));
        return $this;
    }


    /**
     * Checkt of een bepaalde role een bepaalde resource mag uitvoeren
     *
     * @param string $role De role naam
     * @param string $resource De resource naam
     * @return boolean True als het goegestaan is, anders false
     */
    public function isAllowed($role,$resource){
        $resource = $this->getResource($resource);
        return (bool)($resource->getBit() & $this->getRule($this->getRole($role)));
    }
}


class Awf_Acl_Role {

    /**
     * Naam van de role
     *
     * @var string
     */
    protected $name;

    /**
     * Maakt instantie aan met de naam
     *
     * @param string $name
     */
    public function __construct($name){
        $this->name = $name;
    }

    /**
     * De naam van de Role
     *
     * @return string
     */
    public function __toString(){
        return (string)$this->name;
    }
}


class Awf_Acl_Resource {

    /**
     * Naam van de resource
     *
     * @var string
     */
    protected $name;

    /**
     * De bit (1,2,4,8,16,...) van de resource
     *
     * @var int
     */
    protected $bit;

    /**
     * De exponent die onthoud welke 2^$exp de volgende resource moet krijgen
     *
     * @var int
     */
    static public $exp = 0;

    /**
     * Maak een resource aan en geef hem een $bit getalletje
     *
     * @param string $name
     * @param Awf_Acl_Resource/null Als deze resource een andere resource moet overerven
     */
    public function __construct($name){
        $this->bit = pow(2,self::$exp++);
        $this->name = $name;
    }

    /**
     * Returnt het resource bit getal
     *
     * @return int
     */
    public function getBit(){
        return (int)$this->bit;
    }

    /**
     * Returnt de resource naam
     *
     * @return string
     */
    public function __toString(){
        return (string)$this->name;
    }

}

class App_Lib_Acl extends Awf_Acl {

    protected $role;

    public function __construct($role){
        $this->role = $role;

        // Voeg resources toe (merk de chaining (korte syntax) op)
        $this->add(new Awf_Acl_Resource('beheer'))
            ->add(new Awf_Acl_Resource('seo'))
            ->add(new Awf_Acl_Resource('changeLayout'))
            ->add(new Awf_Acl_Resource('add_comment'));

        // Voeg roles toe
        $this->add(new Awf_Acl_Role('gast'))
            ->add(new Awf_Acl_Role('beheerder'))
            ->add(new Awf_Acl_Role('admin'));

        // Vertel de Awf_Acl instantie welke role wat mag
        $this->allow('gast','add_comment')
            // Extend betekend dat de beheerder nu alles mag wat een gast ook mag (comments plaatsen)
            ->extend('beheerder','gast')
            ->allow('beheerder','beheer')
            // De admin mag nu alles wat een beheerder ook mag
            ->extend('admin','beheerder')
            ->allow('admin','seo')
            ->allow('admin','changeLayout')
            // Voorbeeld: een admin mag geen comment plaatsen
            ->deny('admin','add_comment');

        // Check of een actie die je wilt doen, toegestaan is.
        var_dump($this->isAllowed('admin','add_comment')); // bool(false)

    }

    function resAllowed($resource){
        if(!empty($this->role)){
            return $this->isAllowed($this->role,$resource);
        }
        return false;
    }

}

?>