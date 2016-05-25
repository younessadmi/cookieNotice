<?php
if(!defined('_PS_VERSION_'))
    exit;

class CookieNotice extends Module {
    public $updateValue = [
        'cookienotice_message',
        'cookienotice_button_text',
        'cookienotice_cookie_expiration',
        'cookienotice_law',
        'cookienotice_law_link',
        'cookienotice_position',
        'cookienotice_animation',
        'cookienotice_text_color',
        'cookienotice_background_color',
    ];


    public function __construct() {
        $this->name = 'cookienotice';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'ESGI';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Cookie Notice');
        $this->description = $this->l('Cookie Notice vous permet d\'informer de manière élégante vos utilisateurs que vous utilisez des cookies afin d\'être en accord avec la loi européenne');

        $this->confirmUninstall = $this->l("Attention, vous êtes sur le point de désinstaller le module");
    }

    public function install() {
        $this->updateValue['cookienotice_message'] = "Nous utilisons des cookies pour vous garantir la meilleure expérience sur notre site. Si vous continuez à utiliser ce dernier, nous considérerons que vous acceptez l'utilisation des cookies.";
        $this->updateValue['cookienotice_button_text'] = "J'accepte";
        $this->updateValue['cookienotice_cookie_expiration'] = 60*60*24*30*6; // 6 mois
        $this->updateValue['cookienotice_law'] = "Non";
        $this->updateValue['cookienotice_law_link'] = "https://www.cnil.fr/fr/cookies-traceurs-que-dit-la-loi";
        $this->updateValue['cookienotice_position'] = "Bas";
        $this->updateValue['cookienotice_animation'] = "Aucune";
        $this->updateValue['cookienotice_text_color'] = "#FFFFFF";
        $this->updateValue['cookienotice_background_color'] = "#000000";


        foreach($this->updateValue as $key => $value){
            if(!Configuration::updateValue($key, $value)){
                return false;   
            }
        }

        if(!parent::install() || !$this->registerHook('header')){
            return false;
        }

        return true;
    }

    public function uninstall() {
        foreach($this->updateValue as $key){
            if(!Configuration::deleteByName($key)){
                return false;   
            }
        }

        if(!parent::uninstall()){
            return false;
        }

        return true;
    }

    public function getContent() {
        $output = null;

        if(Tools::isSubmit('submit'.$this->name)){
            foreach($this->updateValue as $key){
                $option = strval(Tools::getValue($key));

                if(!$option || empty($option) || !Validate::isGenericName($option)){
                    $error = $this->l('Configuration invalide concernant: '.$key);
                }else{
                    Configuration::updateValue($key, $option);                    
                    $success = $this->l('Paramètres sauvegardés');
                }
                unset($option);
            }
            if(isset($error)){
                $output .= $this->displayError($error);   
            }
            if(isset($success) && !isset($error)){
                $output .= $this->displayConfirmation($success);
            }
        }
        return $output.$this->displayForm();
    }

    public function displayForm() {
        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Paramètres'),
            ],
            'input' => [
                [
                    'type' => 'textarea',
                    'label' => $this->l('Message'),
                    'desc' => $this->l("Saisir le message d'avis relatif aux cookies."),
                    'name' => 'cookienotice_message',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Texte du bouton'),
                    'desc' => $this->l("Le texte de l'option pour accepter l'utilisation des cookies et faire disparaitre la notification"),
                    'name' => 'cookienotice_button_text',
                    'size' => 20,
                    'required' => true,
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Expiration des cookies'),
                    'desc' => $this->l('La durée de stockage des cookies.'),
                    'name' => 'cookienotice_cookie_expiration',
                    'required' => true,
                    'options' => [
                        'query' => [
                            [
                                'time' => 60*60*24,
                                'name' => '1 jour',
                            ],
                            [
                                'time' => 60*60*24*7,
                                'name' => '1 semaine',
                            ],
                            [
                                'time' => 60*60*24*30,
                                'name' => '1 mois',
                            ],
                            [
                                'time' => 60*60*24*30*3,
                                'name' => '3 mois',
                            ],
                            [
                                'time' => 60*60*24*30*6,
                                'name' => '6 mois',
                            ],
                            [
                                'time' => 60*60*24*365,
                                'name' => '1 année',
                            ],
                            [
                                'time' => 60*60*24*365*50,
                                'name' => 'Illimité',
                            ],
                        ],
                        'id' => 'time',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Legislation'),
                    'desc' => $this->l('Ajouter un lien vers des informations concernant la loi'),
                    'name' => 'cookienotice_law',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'law_yes',
                            'value' => true,
                            'label' => $this->l('Oui')
                        ],
                        [
                            'id' => 'law_no',
                            'value' => false,
                            'label' => $this->l('Non')
                        ]
                    ],
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Adresse du lien'),
                    'desc' => $this->l("L'adresse du lien pour les infos supplémentaires concernant la législation"),
                    'name' => 'cookienotice_law_link'
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Position'),
                    'desc' => $this->l('Sélectionner la position de votre avis relatif aux cookies.'),
                    'name' => 'cookienotice_position',
                    'is_bool' => true,  
                    'values' => [
                        [
                            'id' => 'position_top',
                            'value' => 'Haut',
                            'label' => $this->l('Haut')
                        ],
                        [
                            'id' => 'position_bottom',
                            'value' => 'Bas',
                            'label' => $this->l('Bas')
                        ]
                    ],
                    'required' => true,
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Animation'),
                    'desc' => $this->l("Animation de l'acceptation de l'avis relatif aux cookies."),
                    'name' => 'cookienotice_animation',
                    'values' => [
                        [
                            'id' => 'animation_none',
                            'value' => 'Aucune',
                            'label' => $this->l('Aucune')
                        ],
                        [
                            'id' => 'animation_fondu',
                            'value' => 'Fondu',
                            'label' => $this->l('Fondu')
                        ],
                        [
                            'id' => 'animation_glissement',
                            'value' => 'Glissement',
                            'label' => $this->l('Glissement')
                        ]
                    ],
                    'required' => true,
                ],
                [
                    'type' => 'color',
                    'label' => $this->l('Couleur du texte'),
                    'name' => 'cookienotice_text_color',
                    'required' => true,
                ],
                [
                    'type' => 'color',
                    'label' => $this->l('Couleur de fond'),
                    'name' => 'cookienotice_background_color',
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Enregistrer'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

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
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        foreach($this->updateValue as $key){
            // Load current value
            $helper->fields_value[$key] = Configuration::get($key);
        }

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayHeader() {
        $smartyValues = [];
        foreach($this->updateValue as $key){
            $smartyValues[$key] = Configuration::get($key);
        }
        if(isset($_COOKIE['cookienotice_accepted'])){
            $smartyValues['cookienotice_accepted'] = 'on';
        }else $smartyValues['cookienotice_accepted'] = 'off';
        
        $this->context->smarty->assign($smartyValues);

        $this->context->controller->addCSS($this->_path.'css/cookienotice.css', 'all');
        $this->context->controller->addJS($this->_path.'js/cookienotice.js');
        return $this->display(__FILE__, 'cookienotice.tpl');
    }
}
