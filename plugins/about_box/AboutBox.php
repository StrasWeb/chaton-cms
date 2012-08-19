<?php
/**
 * AboutBox class
 * Displays the box
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
     * Class used to display a div with additional information
     * (generally on the right)
     *
     * PHP Version 5.3.6
     * 
     * @category Plugin
     * @package  Chaton
     * @author   Pierre Rudloff <rudloff@strasweb.fr>
     * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
     * @link     http://cms.strasweb.fr
     */
    class AboutBox extends Plugin
    {
        public $content;
        public $title;
        
        /**
         * AboutBox constructor
         * 
         * @param str $dir  Directory of the plugin
         * @param str $lang Lang of the instance
         * 
         * @return void
         * */
        function __construct($dir, $lang="++")
        {
            $this->title=$this->getParam("aboutbox_title", $lang);
            $this->content=$this->getParam("aboutbox_content", $lang);
            parent::__construct($dir); 
        }
    }
}
    ?>
