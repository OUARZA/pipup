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
     public function preSave()
    {
        log::add('pipup', 'debug', 'preSave eqLogic');
        $position = $this->getConfiguration('position'); 

        if (!is_numeric($position)) {
            $this->setConfiguration('position', 2);
        }

        $cmds = $this->getCmd();
            foreach($cmds as $cmd) {
                log::add('pipup', 'debug', 'foreach cmd getLogicalId: '.$cmd->getLogicalId());
    
                if ($cmd->getLogicalId() == 'notify') {
                    log::add('pipup', 'debug', 'preSave cmd notify');
        
                    if (empty($cmd->getConfiguration('titleColor'))) {
                        log::add('pipup', 'debug', 'preSave cmd. notify. avant titlecolor');
        
                        $cmd->setConfiguration('titleColor', "#000000");
                        log::add('pipup', 'debug', 'preSave cmd. notify. apres titlecolor: '.$cmd->getConfiguration('titleColor'));
    
                    }
                    if (empty($cmd->getConfiguration('messageColor'))) {
                        $cmd->setConfiguration('messageColor', "#000000");
                    }
                    if (empty($cmd->getConfiguration('backgroundColor'))) {
                        $cmd->setConfiguration('backgroundColor', "#ffffff");
                    }
                    if (empty($cmd->getConfiguration('url'))) {
                        $cmd->setConfiguration('url', 'https://www.pinclipart.com/picdir/big/85-851186_push-notifications-push-notification-icon-png-clipart.png');
                    }
                }

                //  $cmd->setLogicalId($cmd->getName());
                //  log::add('pipup', 'debug', 'foreach cmd getEqLogic_id: '.$this->getLogicalId());
                //  log::add('pipup', 'debug', 'foreach cmd getId: '.$this->getId());                 
                //  log::add('pipup', 'debug', 'foreach cmd setEqLogic_id: '.$this->getLogicalId());

                $cmd->setType('action');
                $cmd->setSubType('message');

                $cmd->save();
            }

    }

    public function postSave()
    {
        // pipup_action
        log::add('pipup', 'debug', 'postSave eqLogic');

        $cmdsCount= count($this->getCmd());

        if ($cmdsCount === 0) {
            // notify
            $notify = $this->getCmd(null, 'notify');
            if (!is_object($notify)) {
                $notify = new pipupCmd();
                $notify->setLogicalId('notify');
                $notify->setIsVisible(1);
                $notify->setName(__('notify', __FILE__));
                $notify->setOrder(0);

                $notify->setConfiguration('titleColor', "#000000");
                $notify->setConfiguration('messageColor', "#000000");
                $notify->setConfiguration('backgroundColor', "#ffffff");
                $notify->setConfiguration('url', 'https://www.pinclipart.com/picdir/big/85-851186_push-notifications-push-notification-icon-png-clipart.png');
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

                $alert->setConfiguration('titleColor', "#ff0000");
                $alert->setConfiguration('messageColor', "#000000");
                $alert->setConfiguration('backgroundColor', "#ffffff");
                $alert->setConfiguration('url', 'https://www.pinclipart.com/picdir/big/94-941341_open-animated-gif-alert-icon-clipart.png');
            }
            $alert->setEqLogic_id($this->getId());
            $alert->setType('action');
            $alert->setSubType('message');
            $alert->save();
            unset($alert);
        } else {
            
        }
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
}

class pipupCmd extends cmd
{
    
    public function preSave() {        
    }


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
        unset($iptv);

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
        unset($duration);

        // Position
        log::add('pipup', 'debug', ' Récupération position', __FILE__);
        $position = $eqlogic->getConfiguration('position');
        if ($position != '') {            
            if(filter_var($position, FILTER_VALIDATE_INT, ["options" => ["min_range"=>0,  "max_range" => 4]])  !== false)
            {
                $configuration->position = $position;
            } else {
                log::add('pipup', 'error', ' Mauvaise valeur de position : '. $position, __FILE__);
                return; 
            }
        } else {
            $configuration->position = 2; // BottomRight
        }
        unset($position);

        // titleSize
        log::add('pipup', 'debug', ' Récupération titleSize', __FILE__);
        $titleSize = $eqlogic->getConfiguration('titleSize');
        if ($titleSize != '') {
            if(filter_var($titleSize, FILTER_VALIDATE_INT))
            {
                $configuration->titleSize = $titleSize;
            } else {
                log::add('pipup', 'error', ' Mauvaise valeur de titleSize : '. $titleSize, __FILE__);
                return; 
            }
        } else {
            $configuration->titleSize = 20;
        }
        unset($titleSize);
        
        // messageSize
        log::add('pipup', 'debug', ' Récupération messageSize', __FILE__);
        $messageSize = $eqlogic->getConfiguration('messageSize');
        if ($messageSize != '') {
            if(filter_var($messageSize, FILTER_VALIDATE_INT))
            {
                $configuration->messageSize = $messageSize;
            } else {
                log::add('pipup', 'error', ' Mauvaise valeur de messageSize : '. $messageSize, __FILE__);
                return; 
            }
        } else {
            $configuration->messageSize = 14;
        }
        unset($messageSize);

        // imageSize
        log::add('pipup', 'debug', ' Récupération imageSize', __FILE__);
        $imageSize = $eqlogic->getConfiguration('imageSize');
        if ($imageSize != '') {
            if(filter_var($imageSize, FILTER_VALIDATE_INT))
            {
                $configuration->imageSize = $imageSize;
            } else {
                log::add('pipup', 'error', ' Mauvaise valeur de imageSize : '. $imageSize, __FILE__);
                return; 
            }
        } else {
            $configuration->imageSize = 240;
        }
        unset($imageSize);

        return $configuration;
    }

    function action($configuration, $options, $type='notify') {
        $eqlogic = $this->getEqLogic();
        $cmd = $eqlogic->getCmd(null, $type);

        $title = $options['title'];
        $message = $options['message'];

        $tmp = new stdClass();
        // Paramétrage Generique
        $tmp->duration= $configuration->duration;
        $tmp->position = $configuration->position;
        $tmp->titleSize = $configuration->titleSize;
        $tmp->messageSize = $configuration->messageSize;

        // Paramètre Action
        $tmp->title = $title;
        $tmp->message = $message;

        // Paramétrage Commande
        $tmp->titleColor = $cmd->getConfiguration('titleColor');
        if (empty($tmp->titleColor)) {
            $tmp->titleColor= "#000000";
        }

        $tmp->messageColor = $cmd->getConfiguration('messageColor');
        if (empty($tmp->messageColor)) {
            $tmp->messageColor= "#000000";
        }

        $tmp->backgroundColor = $cmd->getConfiguration('backgroundColor');
        if (empty($tmp->backgroundColor)) {
            $tmp->backgroundColor= "#ffffff";
        }

        if (!empty($cmd->getConfiguration('url'))) {
            $image = new stdClass();
            $image->uri = $cmd->getConfiguration('url');
            $image->width = $configuration->imageSize;

            $tmp->media = new StdClass();
            $tmp->media->image = $image;
        }

        $data = json_encode($tmp);
        log::add('pipup', 'debug', ' data: '.$data, __FILE__);

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
            // log::add('pipup', 'info', 'info : ' . json_encode($info), __FILE__);

            if ($info["http_code"] == 200) {
                log::add('pipup', 'info', 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'], __FILE__);
                log::add('pipup', 'debug', ' data : ' . $tuData, __FILE__);
            } else {
                log::add('pipup', 'error', ' data : ' . $tuData, __FILE__);
            }
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

        $this->action($configuration, $_options, $this->getLogicalId());
    }
}
