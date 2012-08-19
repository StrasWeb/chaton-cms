<?php
/**
 * BrowserID class
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
require_once __DIR__."/../../classes/Plugin.php";
/**
 * Class used to manage preferences for the BrowserID plugin
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
class BrowserID extends Plugin
{
    public $email;
    
    /**
     * BrowserID constructor
     * 
     * @param str $dir Directory of the plugin
     * 
     * @return void
     * */
    function __construct($dir)
    {
        $this->email=$this->getParam("email");
        parent::__construct($dir); 
    }
}
?>
