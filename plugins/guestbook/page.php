<?php
if(isset($this)){
    global $dom;
    $dom->html->head->addElement("link", null, array("rel"=>"stylesheet", "href"=>"plugins/".$this->dir."/style.css"));
    require_once("recaptchalib.php");
    $dom->html->head->addElement("script", null, array("type"=>"text/javascript", "src"=>"plugins/".$this->dir."/recaptcha.js"));
        $dom->html->body->div->div->section->addElement("div", null, array("class"=>"text", "id"=>$this->dir."Page"));
        
        
        if(isset($_GET["added"])){
            $dom->html->body->div->div->section->div->addElement("div", _("Comment sucessfully added"), array("class"=>"modified"));   
        }
        require_once("plugins/".$this->dir."/GBComment.php");
        $this->comments=GBComment::getAll();
        $dom->html->body->div->div->section->div->addElement("div", null, array("id"=>"comments"));
        foreach($this->comments as $comment){
            $dom->html->body->div->div->section->div->div->addElement("div", null, array("class"=>"comment"));
            $dom->html->body->div->div->section->div->div->div->addElement("blockquote", stripslashes($comment->comment));
            $dom->html->body->div->div->section->div->div->div->addElement("div", null, array("class"=>"commentInfo"));
            $dom->html->body->div->div->section->div->div->div->div->addElement("span", stripslashes($comment->name));
            if(!empty($comment->age)){
            $dom->html->body->div->div->section->div->div->div->div->addElement("span", ", ".stripslashes($comment->age)." "._("years old"));
            }
             if(!empty($comment->location)){
            $dom->html->body->div->div->section->div->div->div->div->addElement("span", ", ".stripslashes($comment->location));
             }
        }
        
        $comment=new GBComment();
        
      $dom->html->body->div->div->section->div->addElement("form", null, array("id"=>"guestbook", "action"=>"index.php?plugin=".$this->dir, "method"=>"post"));
      
    if(isset($_POST["human"]) && empty($_POST["human"])){
    $privatekey = "6LeAXs4SAAAAAJipYOgp3frxP2K1I62P1PQwPyCU";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

    if ($resp->is_valid) {
     if(empty($_POST["comment"])){
      $dom->html->body->div->div->section->div->form->addElement("div", _("Empty comment!"), array("class"=>"error"));  
     }else if(empty($_POST["name"])){
      $dom->html->body->div->div->section->div->form->addElement("div", _("You must enter a name!"), array("class"=>"error"));  
      }else if(!empty($_POST["age"]) && !ctype_digit($_POST["age"])){
              $dom->html->body->div->div->section->div->form->addElement("div", _("Age must be a number!"), array("class"=>"error"));  
     }else{
        if($comment->add($this->dir)){
            header("Location: index.php?added=true&plugin=".$this->dir);
        }
     }
    } else {
		$dom->html->body->div->div->section->div->form->addElement("div", _("You must correctly fill the captcha!"), array("class"=>"error"));  
	}
	}
		
      $dom->html->body->div->div->section->div->form->addElement("label", _("Add a comment:"), array("for"=>"comment"));
      $dom->html->body->div->div->section->div->form->addElement("br");
      $dom->html->body->div->div->section->div->form->addElement("textarea", $comment->comment, array("placeholder"=>_("Your text here"), "name"=>"comment", "id"=>"comment", "rows"=>10, "required"=>true));
       //reCaptcha
		$publickey = "6LeAXs4SAAAAALMXqv9TRJdJNpypL4INpUOrp_uW "; 
		$captcha=$dom->createDocumentFragment();
		$captcha->appendXML(stripslashes(recaptcha_get_html($publickey)));
		$dom->html->body->div->div->section->div->form->appendchild($captcha);
		//
      $dom->html->body->div->div->section->div->form->addElement("label", _("Your name:")." ", array("for"=>"name"));
      $dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"text", "id"=>"name", "name"=>"name", "required"=>true, "value"=>$comment->name));
      $dom->html->body->div->div->section->div->form->addElement("br");
      $dom->html->body->div->div->section->div->form->addElement("label", _("Your age:")." ", array("for"=>"age"));
      $dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"number", "id"=>"age", "name"=>"age", "min"=>1, "max"=>150, "value"=>$comment->age));
      $dom->html->body->div->div->section->div->form->addElement("br");
      $dom->html->body->div->div->section->div->form->addElement("label", _("Your location:")." ", array("for"=>"location"));
      $dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"text", "id"=>"location", "name"=>"location", "value"=>$comment->location));
       $dom->html->body->div->div->section->div->form->addElement("br");
       $dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"text", "id"=>"human", "name"=>"human"));
      $dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"submit", "value"=>_("Send")));
}
?>
