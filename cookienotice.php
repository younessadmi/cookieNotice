<?php
if(!defined('_PS_VERSION_'))
    exit;

class CookieNotice extends Module {
    public function __construct() {
        $this->name = 'cookienotice';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'ESGI';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array(
            'min' => '1.6',
            'max' => _PS_VERSION_
        );
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Cookie Notice');
        $this->description = $this->l('Cookie Notice vous permet d\'informer de manière élégante vos utilisateurs que vous utilisez des cookies afin d\'être en accord avec la loi européenne');

        $this->confirmUninstall = $this->l("Attention, vous êtes sur le point de désinstaller le module");
    }

    public function install() {
        if(!parent::install() || !Configuration::updateValue('cookienotice_message', 'Nous utilisons des cookies pour vous garantir la meilleure expérience sur notre site. Si vous continuez à utiliser ce dernier, nous considérerons que vous acceptez l&#039;utilisation des cookies.') || !$this->registerHook('leftColumn'))
            return false;

        return true;
    }

    public function uninstall() {
        if(!parent::uninstall() || !Configuration::deleteByName('cookienotice_message'))
            return false;

        return true;
    }

    public function getContent() {
        $output = null;

        if(Tools::isSubmit('submit'.$this->name)){
            $myoption_txt = strval(Tools::getValue('cookienotice_message'));
            if(!$myoption_txt || empty($myoption_txt) || !Validate::isGenericName($myoption_txt))
                $output .= $this->displayErrors($this->l('Configuration invalide'));
            else{
                Configuration::updateValue('cookienotice_message', $myoption_txt);
                $output .= $this->displayConfirmation($this->l('Paramètres sauvegardés'));
            }
        }
        return $output.$this->displayForm();
    }

    public function displayForm() {
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Texte à afficher'),
                    'name' => 'cookienotice_message',
                    'size' => 20,
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Envoyer'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['cookienotice_message'] = Configuration::get('cookienotice_message');

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayLeftColumn($params) {
        $this->context->smarty->assign(
            array(
                'my_module_name' => Configuration::get('cookienotice_message')
            )
        );

        return $this->display(__FILE__, 'cookienotice.tpl');
    }
}