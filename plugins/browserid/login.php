<?php
/**
 * Login check
 *
 * PHP Version 5.3.6
 * 
 * @category PHP
 * @package  AssoQuest
 * @author   StrasWeb <assoquest@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @link     http://assoquest.strasweb.fr
 */

$curl = curl_init("https://browserid.org/verify");
if (isset($_GET["assertion"])) {
    include_once "../../classes/dom-enhancer/Error.php";
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt(
        $curl, CURLOPT_POSTFIELDS, "assertion=".strval(
            $_GET["assertion"]
        )."&audience=localhost"
    );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response=json_decode(strval(curl_exec($curl)));
    curl_close($curl);
     

    if ($response->status==="okay") {
        session_start();
        include_once __DIR__."/../../classes/Config.php";
        $config=new Config();
        include_once __DIR__."/BrowserID.php";
        $plugin=new BrowserID();
        if ($response->email == $plugin->email) {
            $_SESSION["login"]=true;
            header("Location: ../../admin/index.php");
        } else {
            trigger_error(_("Wrong e-mail !"));
        }
    } else {
        trigger_error($response->reason);
    }
} else {
    header("Location: ../../index.php");
}
?>
