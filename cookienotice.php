<?php

if(!defined('_PS_VERSION_')){
    exit;
}

class CookieNotice extends Module {
    public function __construct(){
        parent::__construct();
        
        $this->name = 'cookienotice';   
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'ESGI';
        $this->need_instance = false;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;
        $this->displayName = $this->l('Cookie Notice');
        $this->description = $this->l('Cookie Notice vous permet d\'informer de manière élégante vos utilisateurs que vous utilisez des cookies afin d\'être en accord avec la loi européenne');
        $this->confirmUninstall = $this->l('Attention, vous êtes sur le point de désinstaller l\'extension');
    }
}