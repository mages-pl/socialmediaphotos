<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    Michał Jendraszczyk
 * @copyright Copyright (c) 2019
 */

 class Socialmediaphotos extends Module { 
     public function __construct() { 
         
        $this->name = 'socialmediaphotos';
        $this->tab = 'advertising_marketing';
        $this->displayName = 'Upland Social Media Photos';
        $this->version = '1.0.0';
        $this->author = 'Michał Jendraszcyk';
        $this->need_instance = 0;

        $this->description = $this->l('Share yours photos product from social media');
        $this->confirmUninstall = $this->l('Are you sure you want to delete ?');
        
        
        $this->bootstrap = true;
        parent::__construct();
     }

     public function install() {
       
      Configuration::updateValue('smediaphotos_mail','m.jendraszczyk@upland.digital');
      // m.jendraszczyk@upland.digital
        $createTableInspiration = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'social_inspirations (`id` INT(16) NOT NULL AUTO_INCREMENT ,`email` VARCHAR(255) NOT NULL , `photo` VARCHAR(255) NOT NULL, `social_url` VARCHAR(255) NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
        DB::getInstance()->Execute($createTableInspiration, 1, 0);

        return parent::install();// && $this->registerHook('ShoppingCartExtra');
     }

     public function uninstall() { 
        return parent::uninstall();
     }
     public function renderForm() { 

        $fields_form = [];

        $helper = new HelperForm();

        $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Settings'),
        ),
        'input' => array(
             array(
                'label' => $this->l('E-mail to send notification about sent inspiration'),
                'desc' => $this->l('Set email where inpiration should sent'),
                'type' => 'text',
                'required' => true,
                'name' => 'smediaphotos_mail',
                ),
                array(
                'label' => $this->l('Time delay between next send inspiration'),
                'desc' => $this->l('Set time delay which is requred to send next inspiration'),
                'type' => 'text',
                'required' => true,
                'name' => 'smediaphotos_time_delay',
                ),
        ),
        'submit' => array(
        'title' => $this->l('Save'),
        'class' => 'btn btn-default pull-right',
        ),
        );

       $helper->submit_action = 'saveInspirationsConfig';
       $helper->tpl_vars['fields_value']['smediaphotos_mail'] = Configuration::get('smediaphotos_mail');
       $helper->tpl_vars['fields_value']['smediaphotos_time_delay'] = Configuration::get('smediaphotos_time_delay');
       

       return $helper->generateForm($fields_form);
     }
     public function postProcess() { 

        if(Tools::isSubmit('saveInspirationsConfig')) { 
            Configuration::updateValue('smediaphotos_mail',Tools::getValue('smediaphotos_mail'));
            Configuration::updateValue('smediaphotos_time_delay',Tools::getValue('smediaphotos_time_delay'));
        }
     }
     public function getContent() { 
        return $this->postProcess().$this->renderForm();
     }
 }