<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class pipup extends eqLogic
{
    /*     * *************************Attributs****************************** */

    /*
   * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
   * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
	public static $_widgetPossibility = array();
   */

    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     * */
    // public static function cron()
    // {
        
    // }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert()
    {
    }

    public function postInsert()
    {
    }

    public function preSave()
    {
    }

    public function postSave()
    {
        // pipup_action
        log::add('pipup', 'debug', 'postSave');

        // notify
        $notify = $this->getCmd(null, 'notify');
        if (!is_object($notify)) {
            $notify = new pipupCmd();
            $notify->setLogicalId('notify');
            $notify->setIsVisible(1);
            $notify->setName(__('notify', __FILE__));
            $notify->setOrder(0);
        }
        $notify->setEqLogic_id($this->getId());
        $notify->setType('action');
        $notify->setSubType('message');
        $notify->save();
        unset($notify);

        // alerte
        $alert = $this->getCmd(null, 'alert');
        if (!is_object($alert)) {
            $alert = new pipupCmd();
            $alert->setLogicalId('alert');
            $alert->setIsVisible(1);
            $alert->setName(__('alert', __FILE__));
            $alert->setOrder(1);
        }
        $alert->setEqLogic_id($this->getId());
        $alert->setType('action');
        $alert->setSubType('message');
        $alert->save();
        unset($alert);
    }

    public function preUpdate()
    {
    }

    public function postUpdate()
    {
        
    }

    public function preRemove()
    {
    }

    public function postRemove()
    {
    }

    /*
     * Non obligatoire : permet de modifier l'affichage du widget (également utilisable par les commandes)
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire : permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire : permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class pipupCmd extends cmd
{
    /*     * *************************Attributs****************************** */

    /*
      public static $_widgetPossibility = array();
    */

    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */


    /**
     * Récupérer la configuration de l'équipement
     */
    private function getMyConfiguration()
    {
        $configuration = new StdClass();

        $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this

        // Lecture et Analyse de la configuration

        // IP TV
        log::add('pipup', 'debug', ' Récupération iptv', __FILE__);
        $iptv = $eqlogic->getConfiguration('iptv');
        if ($iptv != '') {
            if(filter_var($iptv, FILTER_VALIDATE_IP))
            {
                $configuration->iptv = $iptv;
            } else {
                log::add('pipup', 'error', ' Mauvaise valeur de iptv : '. $iptv, __FILE__);
                return; 
            }
        } else {
            log::add('pipup', 'error', ' Pas de iptv', __FILE__);
            return;
        }

        // IP TV
        log::add('pipup', 'debug', ' Récupération duration', __FILE__);
        $duration = $eqlogic->getConfiguration('duration');
        if ($duration != '') {
            if(filter_var($duration, FILTER_VALIDATE_INT))
            {
                $configuration->duration = $duration;
            } else {
                log::add('pipup', 'error', ' Mauvaise valeur de duration : '. $duration, __FILE__);
                return; 
            }
        } else {
            $configuration->duration = 30;
        }

        return $configuration;
    }

    private function action($configuration, $options, $type='notify') {

        $titleColor = "#ff0000";
        // $title = "Notification";
        // $message = "Il faut penser à sortir la poubelle !";
        $position = 2;
        $titleSize = 20;
        $messageColor = "#000000";
        $messageSize = 14;
        $backgroundColor = "#ffffff";
        $media = "https://www.fenetre24.com/fileadmin/images/fr/porte-fenetre/pvc/lightbox/double-porte-fenetre.jpg";

        switch ($type) {
            case 'notify':
                // $title = 'Notification';
                $titleColor = "#000000";
                $media = 'https://www.pinclipart.com/picdir/big/85-851186_push-notifications-push-notification-icon-png-clipart.png';
                break;
            case 'alert':
                // $title = 'Alerte';
                $titleColor = "#ff0000";
                $media ='https://www.pinclipart.com/picdir/big/94-941341_open-animated-gif-alert-icon-clipart.png';
                break;
            default:
                // $title = 'Titre';
                break;
        }
        $title = $options['title'];
        $message = $options['message'];

        $tmp = new stdClass();
        $tmp->duration= $configuration->duration;
        $tmp->position = $position;
        $tmp->title = $title;
        $tmp->titleColor = $titleColor;
        $tmp->titleSize = $titleSize;
        $tmp->message = $message;
        $tmp->messageColor = $messageColor;
        $tmp->messageSize = $messageSize;
        $tmp->backgroundColor = $backgroundColor;

        $image = new stdClass();
        $image->uri = $media;
        $image->width = 240;

        $tmp->media = new StdClass();
        $tmp->media->image = $image;
        $data = json_encode($tmp);

        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL, "http://".$configuration->iptv.":7979/notify");
        // curl_setopt($tuCurl, CURLOPT_PORT , 7979);
        curl_setopt($tuCurl, CURLOPT_VERBOSE, 0);
        curl_setopt($tuCurl, CURLOPT_HEADER, 0);
        // curl_setopt($tuCurl, CURLOPT_SSLVERSION, 3);
        // curl_setopt($tuCurl, CURLOPT_SSLCERT, getcwd() . "/client.pem");
        // curl_setopt($tuCurl, CURLOPT_SSLKEY, getcwd() . "/keyout.pem");
        // curl_setopt($tuCurl, CURLOPT_CAINFO, getcwd() . "/ca.pem");
        curl_setopt($tuCurl, CURLOPT_POST, 1);
        // curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-length: ".strlen($data)));

        $tuData = curl_exec($tuCurl);
        if(!curl_errno($tuCurl)) {
            $info = curl_getinfo($tuCurl);
            log::add('pipup', 'info', 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'], __FILE__);
            log::add('pipup', 'debug', ' data : ' . $tuData, __FILE__);

        } else {
            log::add('pipup', 'error', curl_error($tuCurl), __FILE__);
        }

        curl_close($tuCurl);
    } 

    // Exécution d'une commande  
    public function execute($_options = array())
    {
        log::add('pipup', 'info', ' **** execute ****'.$this->getLogicalId(), __FILE__);

        $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
        log::add('pipup', 'info', ' Objet : ' . $eqlogic->getName(), __FILE__);

        // Lecture et Analyse de la configuration
        $configuration = $this->getMyConfiguration();
        log::add('pipup', 'debug', ' configuration :' . json_encode((array)$configuration));

        switch ($this->getLogicalId()) {
            case 'notify': 
                $this->action($configuration, $_options, 'notify');
                break;
            case 'alert':
                $this->action($configuration, $_options, 'alert');
                break;
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}
