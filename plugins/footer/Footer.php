<?php
/**
 * Footer class
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom) || isset($this)) {
    include_once "classes/Plugin.php";
    
    /**
    * Class used to manage the custom footer
    *
    * PHP Version 5.3.6
    * 
    * @category Plugin
    * @package  Chaton
    * @author   Pierre Rudloff <rudloff@strasweb.fr>
    * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
    * @link     http://cms.strasweb.fr
    */
    class Footer extends Plugin
    {
        public $content;
        public $title;
        
        /**
         * Footer constructor
         * 
         * @param string $dir  Plugin directory
         * @param string $lang Current locale
         * 
         * @return void
         * */
        function __construct($dir, $lang=null)
        {
            global $config;
            if (!isset($lang)) {
                if ($config->multilingual) {
                    $lang=$config->lang;   
                } else {
                    $lang="++";
                }
            }
            $this->default=$this->getParam("footer_default", $lang);
            $this->content=$this->getParam("footer_content", $lang);  
            parent::__construct($dir); 
        }
    }
}
?>
