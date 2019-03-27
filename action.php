<?php
/**
 * DokuWiki Plugin nstemplate (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_nstemplate extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
       $controller->register_hook('COMMON_PAGETPL_LOAD', 'BEFORE', $this, 'handle_common_pagetpl_load');
    }

    public function handle_common_pagetpl_load(Doku_Event &$event, $param) {
        global $conf;
        $id = $event->data['id'];

        $path = dirname(wikiFN($id));
        $tpl = '';
        if(@file_exists($path.'/'.$this->getConf('nstemplate').'.txt')){
            $event->data['tplfile'] = $path.'/'.$this->getConf('nstemplate').'.txt';
        }else{
            // search upper namespaces for templates
            $len = strlen(rtrim($conf['datadir'],'/'));
            while (strlen($path) >= $len){
                if(@file_exists($path.'/'.$this->getConf('inheritednstemplate').'.txt')){
                    $event->data['tplfile'] = $path.'/'.$this->getConf('inheritednstemplate').'.txt';
                    break;
                }
                $path = substr($path, 0, strrpos($path, '/'));
            }
        }
        return true;
    }

}

// vim:ts=4:sw=4:et:
