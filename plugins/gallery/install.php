<?php
/**
 * Gallery plugin
 * Install script
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($this) && !$this->installed) {
    $query=file_get_contents("plugins/".$this->dir."/install.sql");  
    $query =sprintf(
        $query, $config->prefix."chaton_gallery_images",
        $config->prefix."chaton_gallery"
    ); 
    $query = $config->sql->prepare($query);
    $result=$query->execute();
    if ($result) {
        return true;
    } else {
        print_r($query->errorInfo());
        return false;
    }

}
?>
