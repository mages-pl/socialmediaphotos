<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    Michał Jendraszczyk
 * @copyright Copyright (c) 2019
 */

class SocialmediaphotosShareModuleFrontController extends ModuleFrontController {
    
    // public $php_self = 'share'; 
     //public $php_self = 'socialmediaphotosshare';
        public $display_column_left = false;
        public $auth = false; // required login
        public $authRedirection = true;

     public function setMedia()
    {
        parent::setMedia();
        $this->addCSS(_MODULE_DIR_.'/socialmediaphotos/views/css/socialmediaphotos.css');
        $this->addJS(_MODULE_DIR_.'/socialmediaphotos/views/js/socialmediaphotos.js');
    }
    public function init(){
    parent::init();
    }
 
    public function initContent(){
        parent::initContent();
        $this->context->smarty->assign("last_sent_inspiration",$this->context->cookie->last_sent_inspiration);
        $this->context->smarty->assign("current_time_inspiration",time());
        $this->context->smarty->assign("time_delay_inspiration",time()-$this->context->cookie->last_sent_inspiration);
        $this->context->smarty->assign("time_on_inspiration", date('Y-m-d H:i:s', $this->context->cookie->last_sent_inspiration+(int)Configuration::get('smediaphotos_time_delay')));
        $this->context->smarty->assign("config_time_delay_inspiration",Configuration::get('smediaphotos_time_delay'));
        $this->setTemplate('socialmediaphotos.tpl');
    }

    public function postProcess() { 
        if (Tools::isSubmit('submitMessage')) {
            if ((time()-$this->context->cookie->last_sent_inspiration) > (int)Configuration::get('smediaphotos_time_delay')) {

            if(!empty(Tools::getValue('mediaphotos_socialurl'))) {

$photos = [];
try {
foreach($_FILES['mediaphotos_photo']['tmp_name'] as $key => $file) { 

    $getResolution = getimagesize($_FILES['mediaphotos_photo']['tmp_name'][$key]);

    //if(($getResolution[0] >= 1920) && ($getResolution[1] >= 1080)) {

        if (isset($_FILES['mediaphotos_photo']) && isset($_FILES['mediaphotos_photo']['tmp_name'][$key]) && !empty($_FILES['mediaphotos_photo']['tmp_name'][$key])) {
                if (($_FILES['mediaphotos_photo']['size'][$key]) > 2000000) {

                    $this->context->smarty->assign("error",$this->module->l('Error durng uploading file Max size uploaded file is 2MB and must have extension like jpg jpeg png'));
                    // return $this->displayError($this->module->l('Invalid file upload. Max size is 2MB and must have extension jpg, jpeg, png.')).'';
                } else {
                    $ext = Tools::substr($_FILES['mediaphotos_photo']['name'][$key], strrpos($_FILES['mediaphotos_photo']['name'][$key], '.') + 1);
                    $file_name = md5($_FILES['mediaphotos_photo']['name'][$key].time()).'.'.$ext;

                    if (!move_uploaded_file($_FILES['mediaphotos_photo']['tmp_name'][$key], dirname(__FILE__).'/uploads/pictures/'.$file_name)) { ///modules/socialmediaphotos/uploads/pictures/
                        // return $this->displayError($this->module->l('An error occurred while attempting to upload the file.'));
                        $this->context->smarty->assign("error",$this->module->l('Error during uploading file')." ".$_FILES['mediaphotos_photo']['name'][$key]);
                    } else {

                    $addInspiration = 'INSERT INTO '._DB_PREFIX_.'social_inspirations (`email`,`photo`,`social_url`) VALUES ("'.pSQL($this->context->customer->email).'","'.pSQL($file_name).'","'.pSQL($_POST['mediaphotos_socialurl']).'")';
                    DB::getInstance()->Execute($addInspiration, 1, 0);

                    $this->context->cookie->last_sent_inspiration = time();
                    $this->context->smarty->assign("success",$this->module->l('Inspirations sent successfully'));
                    $photos[] = $file_name;
                    $this->context->smarty->assign("uploaded",$photos);

                    if(($key+1) == count($_FILES['mediaphotos_photo']['tmp_name'])) {
                    if(Module::isEnabled('importsalesmanago')) {
                        $addInspirationTag = new importsalesmanago();
                        $addInspirationTag->addVirtualTag($this->context->customer->id, Configuration::get('saimport_virutal_tag_inspiracje'));
                    }

                    $this->sendMail($this->context->customer->email,$photos,$_POST['mediaphotos_socialurl']);
                    }

                    }
                }
            }
    /*}
    else { 
    $this->context->smarty->assign("error",$this->module->l('Picture').' '.$_FILES['mediaphotos_photo']['name'][$key]." ".$this->module->l('is too small')." (".$getResolution[0].'x'.$getResolution[1].") ".$this->module->l('minimal resolution is')."(1920x1080)");
}*/

sleep(3);
flush();
}
} catch(Exception $e) { 
    //echo "Opis problemu: ".$e;
} 
            } else {
                $this->context->smarty->assign("error",$this->module->l('Url to social media is required'));
            }
            } else { 
                $this->context->smarty->assign("error",$this->module->l('You must waiting few minutes for next send inspirations'));
            }
        }
    }
public function sendMail($email,$pictures,$social)  {

    $customer = (new Customer())->getCustomersByEmail($email);

    //     print_r($customer);
    //     exit();

    $picture_content = '<h3>'.$this->module->l('Inspirations from customer').'</h3>';
    $picture_content .= '<h2>'.$customer[0]['firstname'].' '.$customer[0]['lastname'].'</h2>';
    $picture_content .= '<p><strong>'.$this->module->l('Url to social media').':</strong> <a href="'.$social.'">'.$social.'</a></p>';

    foreach($pictures as $key => $picture)  {
        //<img src="http://'.(Tools::getShopDomain(false, false)).'/modules/socialmediaphotos/controllers/front/uploads/pictures/'.$picture.'" alt="Inspiracja #'.$key.'" />
    $picture_content .= '<br/>'.$this->module->l('Url to image').' '.($key+1).':<br/> <a href="http://'.(Tools::getShopDomain(false, false)).'/modules/socialmediaphotos/controllers/front/uploads/pictures/'.$picture.'">http://'.(Tools::getShopDomain(false, false)).'/modules/socialmediaphotos/controllers/front/uploads/pictures/'.$picture.'</a><br/>';    
    }

        Mail::Send(
             Configuration::get('PS_LANG_DEFAULT'),  // id lang
             'contact', // template 
             'Customer '.$customer[0]['firstname'].' '.$customer[0]['lastname'].' sent inspiration', // subject
            array(
                '{email}' => $email, // sender email address
                '{message}' => $picture_content, // template vars
                '{order_name}' => '',
                '{attached_file}' => ''
            ), 
            Configuration::get('smediaphotos_mail'), // to //
            null, //$to_name = 
            null, //$from = 
            null, //$from_name = 
            null, //$file_attachment = 
            null, //$mode_smtp = 
            _PS_MAIL_DIR_, //$template_path = 
            false, //$die = 
            null, //$id_shop
            null, //$bcc
            null // reply_to
        );

}
}