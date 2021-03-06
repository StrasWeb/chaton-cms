<?php
/**
 * Fancybox plugin hook
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($this)) {
    global $dom, $curPage;
    $dom->head->addElement(
        "script", null, array(
            "type"=>"text/javascript",
            "src"=>"plugins/".$this->dir."/fancybox/jquery.fancybox-1.3.4.pack.js"
        )
    );
    $dom->head->addElement(
        "link", null, array(
            "rel"=>"stylesheet",
            "href"=>"plugins/".$this->dir."/fancybox/jquery.fancybox-1.3.4.css",
            "type"=>"text/css", "media"=>"screen"
        )
    );
    $dom->head->addElement(
        "script", null, array(
            "type"=>"text/javascript",
            "src"=>"plugins/".$this->dir."/script.js",
            "id"=>"fancyBoxPluginScript"
        )
    );
    $this->main=$dom->getElementById("main");
    $this->folder="plugins/".$this->dir."/img/".$curPage."/";
    if (is_dir($this->folder) && $handle = opendir($this->folder)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".svn") {
                $this->main->addElement(
                    "a", null, array("class"=>"gallery", "rel"=>"prefetch",
                    "href"=>"plugins/".$this->dir."/img/".$curPage."/".$file)
                );
            }
        }
        closedir($handle);
    }		
}
?>
