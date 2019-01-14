<?php
/**
 * Plugin class
 *
 * PHP Version 5.3.6
 *
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */

 /**
 * Class used manage plugins
 *
 * PHP Version 5.3.6
 *
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
class Plugin
{
    public $name;
    public $enabled;
    public $installed;
    public $dir;
    static $table="plugins";

    /**
     * Get list of all plugins
     *
     * @return array
     * */
    static function getList()
    {
        if ($handle=opendir("plugins")) {
            while (false!==($dir=readdir($handle))) {
                /*On pourrait utiliser is_dir plutôt que !is_file
                 * mais ça a l'air de déconner en local sur Mac... */
                if ($dir!="."
                    && $dir!=".."
                    && $dir!=".svn"
                    && $dir!=".DS_Store"
                    && !is_file($dir)
                ) {
                    $plugins[]=$dir;
                }
            }
            closedir($handle);
            return $plugins;
        }
    }

    /**
     * Plugin constructor
     *
     * @param string $dir Directory of the plugin
     *
     * @return void
     * */
    function __construct($dir)
    {
        global $config;
        $this->dir=$dir;
        $query=file_get_contents("sql/getPlugin.sql");
        $query =sprintf($query, $config->prefix.self::$table);
        if ($query) {
            $query = $config->sql->prepare($query);
            $query->bindValue(":name", $dir, PDO::PARAM_STR);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            if (is_object($result)) {
                $this->installed=$result->installed;
                $this->enabled=$result->enabled;
            }
        }
        $file="plugins/".$dir."/plugin.php";
        if (is_file($file)) {
            include $file;
        } else {
            $this->name=$dir;
        }
        $file="plugins/".$dir."/admin.php";
        if (is_file($file)) {
            $this->admin=true;
        }
    }

    /**
     * Disable a plugin
     *
     * @return bool
     * */
    function disable()
    {
           $this->enabled=false;
           return $this->_update();
    }

    /**
     * Enable a plugin
     *
     * @return bool
     * */
    function enable()
    {
        $this->enabled=true;
        if ($this->installed) {
            return $this->_update();
        } else {
            return $this->_install();
        }
    }

    /**
     * Update the state (enabled/disabled) of a plugin
     *
     * @return bool
     * */
    private function _update()
    {
        global $config;
        $query=file_get_contents("sql/updatePlugin.sql");
        $query =sprintf($query, $config->prefix.self::$table);
        $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $query->bindValue(":enabled", $this->enabled, PDO::PARAM_INT);
        $query->bindValue(":name", $this->dir, PDO::PARAM_STR);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
            return false;
        }
    }

    /**
     * Install a new plugin
     *
     * @return bool
     * */
    private function _install()
    {
        global $config;
        $file="plugins/".$this->dir."/install.php";
        if (is_file($file)) {
            include $file;
        }
        $query=file_get_contents("sql/installPlugin.sql");
        $query =sprintf($query, $config->prefix.self::$table);
        $query = $config->sql->prepare($query);
        $query->bindValue(":enabled", $this->enabled, PDO::PARAM_STR);
        $query->bindValue(":name", $this->dir, PDO::PARAM_STR);
        $query->bindValue(":installed", true, PDO::PARAM_INT);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }
    }

    /**
     * Hook a plugin into the code after the DOM is generated
     *
     * @return void
     * */
    function hook()
    {
        $file="plugins/".$this->dir."/hook.php";
        if (is_file($file)) {
            include $file;
        }
        if (isset($_GET["plugin"]) && $_GET["plugin"]==$this->dir) {
            $dom->getElementById($this->dir."MenuItem")
                ->setAttribute("class", "current");
        }
    }

     /**
     * Hook a plugin into the code before the DOM is generated
     *
     * @return void
     * */
    function preHook()
    {
        $file="plugins/".$this->dir."/pre_hook.php";
        if (is_file($file)) {
            include $file;
        }
    }

    /**
     * Display a plugin page (frontend)
     *
     * @return void
     * */
    function displayPage()
    {
        $file="plugins/".$this->dir."/page.php";
        if (is_file($file)) {
            include $file;
        }
    }

    /**
     * Get a plugin parameter from the param database
     *
     * @param string $param Name of parameter
     * @param string $lang  Lang of parameter (for multilingual version only)
     * @param bool   $test  Only check if parameter exists
     *
     * @return bool
     * */
    static function getParam($param, $lang="++", $test=false)
    {
        global $config;
        $query=file_get_contents(__DIR__."/../sql/getParam.sql");
        $query =sprintf($query, $config->prefix."param");
        $query = $config->sql->prepare($query);
        $query->bindValue(":param", $param);
        $query->bindValue(":lang", $lang);
        $query->execute();
        $result=$query->fetch(PDO::FETCH_OBJ);
        if ($result) {
            if ($test) {
                return true;
            } else {
                return $result->value;
            }
        } else {
            return false;
        }
    }

    /**
     * Set a plugin parameter in the param database
     *
     * @param string $param Name of parameter
     * @param string $value Value of parameter
     * @param string $lang  Lang of parameter (for multilingual version only)
     *
     * @return boolean
     * */
    static function setParam($param, $value, $lang="++")
    {
        global $config;
        if (!self::getParam($param, $lang, true)) {
            $query=file_get_contents("sql/addPluginParam.sql");
        } else {
            $query=file_get_contents("sql/setPluginParam.sql");
        }
        $query =sprintf($query, $config->prefix."param");
        $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $query->bindValue(":param", $param);
        $query->bindValue(":value", $value);
        $query->bindValue(":lang", $lang);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            print("Plugin::setParam: ");
            print_r($query->errorInfo());
            return false;
        }
    }


}

?>
