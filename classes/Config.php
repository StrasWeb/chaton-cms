<?php
/**
 * Config class
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

/**
 * Manage Chaton's config
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
class Config
{
    /*
     * @var int
     * */
    public $shortver;
    
    /**
     * Config constructor
     * 
     * @return void
     * */
    function __construct()
    {
        global $admin, $locale;
        $this->fullpath=isset(
            $_SERVER["HTTPS"])?
            "https://":"http://";
        $this->fullpath.=$_SERVER["HTTP_HOST"].
            substr(
                $_SERVER['SCRIPT_NAME'],
                0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1
            );
        //Il faut rajouter un moyen de le choisir.
        date_default_timezone_set('Europe/Paris');
        if (is_file(__DIR__."/../config.php")) {
            include __DIR__."/../config.php";
            try {
                $this->connectDB();
                $query=file_get_contents("sql/getConfig.sql");
                $query=sprintf($query, $this->prefix."main");
                $query=$this->sql->query($query);
                if ($query) {
                    $result=$query->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        foreach ($result as $key=>$value) {
                            $this->$key=stripslashes($value);
                        }
                        if (isset($locale)) {
                            $this->locale=$locale;
                        }
                        if (empty($this->news_title)) {
                            $this->news_title=_("News");
                        }
                    } else {
                        $debug=$query->errorInfo();
                        if ($debug[0]!=00000) {
                            print_r($debug);
                        }
                    }
                }
                if (isset($this->multilingual)&&$this->multilingual) {
                    if (!isset($admin)) {
                        if (isset($_GET["lang"])) {
                            $this->lang=$_GET["lang"];
                            setcookie("lang", $this->lang);
                        } elseif (isset($_COOKIE["lang"])) {
                            $this->lang=$_COOKIE["lang"];
                        }
                    } 
                    $this->languages=unserialize($this->languages);
                    if (!is_array($this->languages)) {
                        $this->languages=array();
                    }
                }
            }
            catch(PDOException$error) {
                //Faudrait faire un peu de debug là
                $this->sql=false;
            }
        }
    }
    
    /**
     * Connect to database
     * 
     * @return void
     * */
    function connectDB()
    {
        $this->sql=new PDO(
            "mysql:dbname=".$this->db.";host=".$this->host,
            $this->user, $this->password
        );
        $this->sql->query("SET NAMES 'utf8';") or die(mysql_error());
    }
    
    /**
     * Update config
     * 
     * @return bool
     * */
    function update()
    {
        $query=file_get_contents("sql/setConfig.sql");
        $query=sprintf($query, $this->prefix."main");
        $query=$this->sql->prepare($query, array(PDO::PARAM_NULL));
        $param=array(
            $this->title, $this->desc, $this->logo, $this->lang,
            $this->homepage, $this->news_title
        );
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }
    }
  
    /**
     * Set the theme to use
     * 
     * @param string $theme Name of the theme
     * 
     * @return book
     * */
    function setTheme($theme)
    {
        $this->theme=$theme;
        $query=file_get_contents("sql/setTheme.sql");
        $query=sprintf($query, $this->prefix."main");
        $query=$this->sql->prepare($query);
        $query->bindValue(":theme", $theme, PDO::PARAM_STR);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }
    }
    
    /**
     * Update the list of enabled languages
     * 
     * @return bool
     * */
    function updateLanguages()
    {
        $array=serialize($this->languages);
        $query=file_get_contents("sql/updateLanguages.sql");
        $query=sprintf($query, $this->prefix."main");
        $query=$this->sql->prepare($query);
        $query->bindValue(":array", $array, PDO::PARAM_STR);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }
    }
  
    /**
    * Add the initial config to the database
    * 
    * @param string $title     Title
    * @param string $user      Admin username
    * @param string $password  Admin password
    * @param string $multiling Is the website multilingual ?
    * @param string $version   Installed version
    * 
    * @return bool
    * */
    function add ($title, $user, $password, $multiling, $version)
    {
        $query=file_get_contents("sql/addConfig.sql");
        $query=sprintf($query, $this->prefix."main");
        $query=$this->sql->prepare($query, array(PDO::PARAM_NULL));
        $salt=uniqid("", true);
        $param=array(
            $title, md5($user.":Chaton CMS Admin ($salt):".$password),
            $multiling, $version, $salt
        );
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }
    }
    
    /**
     * Set a parameter
     * 
     * @param string $param Parameter
     * @param mixed  $value Value
     * 
     * @return bool
     * */
    function setParam($param, $value)
    {
        $query=file_get_contents("sql/setParam.sql");
        $query=sprintf($query, $this->prefix."param", $param);
        $query=$this->sql->prepare($query);
        $query->bindValue(":value", $value);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }
    }
}
?>
