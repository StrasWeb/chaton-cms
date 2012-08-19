<?php
/**
 * Document class
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
require_once "Item.php";
 /**
 * Base class for Page and Article
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
abstract class Document extends Item
{
    public $id;
    public $lang;
    public $title;
    public $content;
    static $table;    
}
?>
