<?php
/**
 * phpfreechat.class.php
 *
 * Copyright © 2006 Stephane Gully <stephane.gully@gmail.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details. 
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, 51 Franklin St, Fifth Floor,
 * Boston, MA  02110-1301  USA
 */

require_once dirname(__FILE__)."/pfccommand.class.php";
require_once dirname(__FILE__)."/pfcglobalconfig.class.php";
require_once dirname(__FILE__)."/pfcuserconfig.class.php";
require_once dirname(__FILE__)."/pfctemplate.class.php";
require_once dirname(__FILE__)."/../lib/utf8/utf8_substr.php";

/**
 * phpFreeChat is the entry point for developpers
 *
 * @example ../demo/demo1_simple.php
 * @author Stephane Gully <stephane.gully@gmail.com>
 */
class phpFreeChat
{
  var $xajax;
  
  function phpFreeChat( &$params )
  {
    if (!is_object($params) &&
        isset($params["debug"]) &&
        $params["debug"])
      require_once dirname(__FILE__)."/../debug/log.php";

    // check if the given parameters is a simple array
    // or a allready created phpfreechat object
    $c = NULL;
    if (is_object($params) &&
        get_class($params) == "pfcglobalconfig")
      $c =& $params;
    else
      $c =& pfcGlobalConfig::Instance( $params );

    // need to initiate the user config object here because it uses sessions
    $u =& pfcUserConfig::Instance();
    
    // then init xajax engine
    if (!class_exists("xajax"))
      if (file_exists($c->xajaxpath."/xajax_core/xajax.inc.php"))
      {
        require_once $c->xajaxpath."/xajax_core/xajax.inc.php";        
        $this->xajax = new xajax($c->server_script_url.(isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] != "" ? "?".$_SERVER["QUERY_STRING"] : ""), 'pfc_');
        if ($c->debugxajax) $this->xajax->setFlag('debug', true);
        $this->xajax->setWrapperPrefix('pfc_');
        $this->xajax->setFlag('waitCursor', false);
        $this->xajax->setFlag('cleanBuffer', false);
        $this->xajax->setFlag('errorHandler', true);
        $this->xajax->registerFunction(array('handleRequest',&$this,'handleRequest'));
        $this->xajax->registerFunction(array('loadStyles',&$this,'loadStyles'));
        $this->xajax->registerFunction(array('loadScripts',&$this,'loadScripts'));
        $this->xajax->registerFunction(array('loadInterface',&$this,'loadInterface'));
        $this->xajax->registerFunction(array('loadChat',&$this,'loadChat'));
        $this->xajax->processRequest();
      }
  }

  /**
   * printJavaScript must be called into html header
   * usage:
   * <code>
   *   <?php $chat->printJavascript(); ?>
   * </code>
   */
  function printJavaScript( $return = false )
  {
    $output = '';
    $c =& pfcGlobalConfig::Instance();
    $u =& pfcUserConfig::Instance();

    // display output
    if ($return)
      return $output;
    else
      echo $output;
  }

  /**
   * printStyle must be called in the header
   * it inserts CSS in order to style the chat
   * usage:
   * <code>
   *   <?php $chat->printStyle(); ?>
   * </code>
   */
  function printStyle( $return = false )
  {
    $c =& pfcGlobalConfig::Instance();

    $output = '';


    /*
    if ($c->isDefaultFile("style.css"))
      $output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$c->getFileUrlByProxy("style.css")."\" />\n";
    else
    {
      // user has a customized stylesheet
      // first of all include the default stylesheet
      // then the user stylesheet
      $defaultstyle = $c->themepath_default."/default/style.css";
      $output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$c->data_public_url."/".$c->getId()."/proxy.php?p=default/style.css\" />\n";
      $output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$c->getFileUrlByProxy("style.css")."\" />\n";
    }
    
    // since php can't be embeded into the css themes files, special styles parameter must be setup here
    if ($c->height != "")
    {
      $output .= "<style type=\"text/css\">";
      $output .= "div#pfc_channels_content { height: ".$c->height."; }";
      $output .= "</style>\n";
    }
    */
    
    if($return)
      return $output;
    else 
      echo $output;
  }

  /**
   * printChat must be called somewhere in the page
   * it inserts necessary html which will receive chat's data
   * usage:
   * <code>
   *   <?php $chat->printChat(); ?>
   * </code>
   */
  function printChat( $return = false )
  {
    $c =& pfcGlobalConfig::Instance();
    $u =& pfcUserConfig::Instance();

    $output = '';

    pfcI18N::SwitchOutputEncoding($c->output_encoding);

    $path = $c->getFilePathFromTheme('chat.js.tpl.php');
    $t = new pfcTemplate($path);
    $t->assignObject($u,"u");
    $t->assignObject($c,"c");
    $output .= $t->getOutput();
    
    pfcI18N::SwitchOutputEncoding();
    /*
    $output .= " // ]]>\n";
    $output .= "</script>\n";
    */
    if($return) 
      return $output;
    else 
      echo $output;
  }
  
  /**
   * Encode special caracteres and remove extra slashes
   */
  function FilterSpecialChar($str)
  {
    //$str = stripslashes($str);
    //    $str = addslashes($str);
    $str = htmlspecialchars($str);
    return $str;
  }
  
  /**
   * Just check the nicknames doesn't start with spaces and is not longer than the max_nick_len
   */
  function FilterNickname($nickname)
  {
    $c =& pfcGlobalConfig::Instance();
    //$nickname = str_replace("\\", "", $nickname); // '\' is a forbidden charactere for nicknames
    $nickname = trim($nickname);
    $nickname = utf8_substr($nickname, 0, $c->max_nick_len);
    return $nickname;
  }
  
  /**
   * search/replace smileys
   */
  function FilterSmiley($msg)
  {
    $c =& pfcGlobalConfig::Instance();
    // build a preg_replace array
    $search = array();
    $replace = array();
    $query = "/(";
    foreach($c->smileys as $s_file => $s_strs)
    {
      foreach ($s_strs as $s_str)
      {
        $s_str = stripslashes($s_str); /* the :'( smileys needs this filter */
      	$query .= preg_quote($s_str,'/')."|";
      	$search[] = "/".preg_quote($s_str,'/')."/";
      	$replace[] = '<img src="'.$s_file.'" alt="'.$s_str.'" title="'.$s_str.'" />';
      }
    }
    $query = substr($query, 0, strlen($query)-1);
    $query .= ")/i";

    $split_words = preg_split($query, $msg, -1, PREG_SPLIT_DELIM_CAPTURE);
    $msg = "";
    foreach($split_words as $word)
      $msg .= preg_replace($search, $replace, $word);
    return $msg;
  }

  
  /**
   * Filter messages before they are sent to container
   */
  function PreFilterMsg($msg)
  {
    $c =& pfcGlobalConfig::Instance();
    if (preg_match("/^\[/i",$msg))
      // add 25 characteres if the message starts with [ : means there is a bbcode
      $msg = utf8_substr($msg, 0, $c->max_text_len+25);
    else
      $msg = utf8_substr($msg, 0, $c->max_text_len);
    $msg = phpFreeChat::FilterSpecialChar($msg);
    
    //    $msg = phpFreeChat::FilterSmiley($msg);

    /*    if ($msg[0] == "\n") $msg = substr($msg, 1); */ // delete the first \n generated by FF
    /* if (strpos($msg,"\n") > 0) $msg  = "<br/>".$msg;
    $msg = str_replace("\r\n", "<br/>", $msg);
    $msg = str_replace("\n", "<br/>", $msg);
    $msg = str_replace("\t", "    ", $msg);*/
    //$msg = str_replace("  ", "&nbsp;&nbsp;", $msg);
    //    $msg = preg_replace('/(http\:\/\/[^\s]*)/i',  "<a href=\"$1\">$1</a>", $msg );
    return $msg;
  }

  /**
   * Filter messages when they are recived from container
   */
  function PostFilterMsg($msg)
  {
    //$c =& pfcGlobalConfig::Instance();
    //    $msg = preg_replace('/('.preg_quote($c->nick,'/').')/i', "<strong>$1</strong>", $msg );
    $msg = preg_replace('/\n/i', "", $msg );
    return $msg;
  }

  function &handleRequest($request)
  {
    $c =& pfcGlobalConfig::Instance();
    $u =& pfcUserConfig::Instance();

    if ($c->debug) ob_start(); // capture output
    
    $xml_reponse = new xajaxResponse();

    // check the command
    $cmdstr      = "";
    $cmdname     = "";
    $clientid    = "";
    $recipient   = "";
    $recipientid = "";
    $param       = "";
    $sender      = "";

    $res = pfcCommand::ParseCommand($request);
    
    $cmdstr      = isset($res['cmdstr']) ? $res['cmdstr'] : $request;
    $cmdname     = strtolower(isset($res['cmdname']) ? $res['cmdname'] : '');
    $clientid    = isset($res['params'][0]) ? $res['params'][0] : '';
    $recipientid = isset($res['params'][1]) ? $res['params'][1] : "";
    $params      = array_slice(is_array($res['params']) ? $res['params'] : array() ,2);
    $param       = implode(" ",$params); // to keep compatibility (will be removed)
    $sender      = $u->nick;
    
    // translate the recipientid to the channel name
    if (isset($u->channels[$recipientid]))
    {
      $recipient = $u->channels[$recipientid]["recipient"];
    }
    if (isset($u->privmsg[$recipientid]))
    {
      $recipient = $u->privmsg[$recipientid]["recipient"];


      // @todo: move this code in a proxy
      if ($cmdname != "update" &&
          $cmdname != "leave" &&  // do not open the pv tab when other user close the tab
          $cmdname != "quit" &&
          $cmdname != "privmsg2")
      {
        // alert the other from the new pv
        // (warn other user that someone talk to him)

        $ct =& $c->getContainerInstance();
        $nickidtopv = $u->privmsg[$recipientid]["pvnickid"];
        $cmdstr = 'privmsg2';
        $cmdp = array();
        $cmdp['param']    = $u->nick;
        $cmdp['params'][] = $u->nick;
        pfcCommand::AppendCmdToPlay($nickidtopv, $cmdstr, $cmdp);
      }

    }
    
    // before playing the wanted command
    // play the found commands into the meta 'cmdtoplay'
    pfcCommand::RunPendingCmdToPlay($u->nickid, $clientid, $xml_reponse);
    
    $cmd =& pfcCommand::Factory($cmdname);
    $cmdp = array();
    $cmdp["clientid"]    = $clientid;
    $cmdp["param"]       = $param;
    $cmdp["params"]      = $params;
    $cmdp["sender"]      = $sender;
    $cmdp["recipient"]   = $recipient;
    $cmdp["recipientid"] = $recipientid;
    if ($cmd != NULL)
    {
      // call the command
      if ($c->debug)
      	$cmd->run($xml_reponse, $cmdp);
      else
      	@$cmd->run($xml_reponse, $cmdp);
    }
    else
    {
      $cmd =& pfcCommand::Factory("error");
      $cmdp = array();
      $cmdp["clientid"]    = $clientid;
      $cmdp["param"]       = _pfc("Unknown command [%s]",stripslashes("/".$cmdname." ".$param));
      $cmdp["sender"]      = $sender;
      $cmdp["recipient"]   = $recipient;
      $cmdp["recipientid"] = $recipientid;
      if ($c->debug)
        $cmd->run($xml_reponse, $cmdp);
      else
        @$cmd->run($xml_reponse, $cmdp);
    }
    
    // do not update twice
    // do not update when the user just quit
    if ($cmdname != "update" &&
      	$cmdname != "quit" &&
      	(!isset($u->nick) || $u->nick != ""))
    {
      // force an update just after a command is sent
      // thus the message user just poster is really fastly displayed
      $cmd =& pfcCommand::Factory("update");
      $cmdp = array();
      $cmdp["clientid"]    = $clientid;
      $cmdp["param"]       = $param;
      $cmdp["sender"]      = $sender;
      $cmdp["recipient"]   = $recipient;
      $cmdp["recipientid"] = $recipientid;
      if ($c->debug)
      	$cmd->run($xml_reponse, $cmdp);
      else
      	@$cmd->run($xml_reponse, $cmdp);
    }
  
    if ($c->debug)
    {
      // capture echoed content
      // if a content not empty is captured it is a php error in the code
      $data = ob_get_contents();
      if ($data != "")
        pxlog("HandleRequest: content=".$data, "chat", $c->getId());
      ob_end_clean();
    }

    // do nothing else if the xml response is empty in order to save bandwidth
    if ($xml_reponse->getCommandCount() == 0) die();
    
    return $xml_reponse;
  }
  

  function loadStyles($theme = 'default', &$xml_reponse)
  {
    if ($xml_reponse == null) $xml_reponse = new xajaxResponse();

    $c =& pfcGlobalConfig::Instance();
    $c->theme = $theme;
    $u =& pfcUserConfig::Instance();

    $js = '';//file_get_contents(dirname(__FILE__).'/client/createstylerule.js');
    $js .= 'var c = $H();';
    $path = $c->getFilePathFromTheme('style.css');
    require_once dirname(__FILE__).'/../lib/csstidy-1.2/class.csstidy.php';
    $css = new csstidy();
    $css->set_cfg('preserve_css',false);


    $css_code = '';
    $t = new pfcTemplate();
    $t->assignObject($u,"u");
    $t->assignObject($c,"c");
    if (!$c->isDefaultFile('style.css'))
    {
      $t->setTemplate($c->themepath_default.'/default/style.css');      
      $css_code .= $t->getOutput();
    }
    $t->setTemplate($c->getFilePathFromTheme('style.css'));
    $css_code .= $t->getOutput();


    $css->parse($css_code);
    foreach($css->css as $k => $v)
    {
      foreach($v as $k2 => $v2)
      {
        $rules = '';
        foreach($v2 as $k3 => $v3)
          $rules .= $k3.':'.$v3.';';
        $js .= "c['".$k2."']='".str_replace("\n", "", $rules)."';\n";
      }
    }
    $js .= "var pfccss = new pfcCSS(); var k = c.keys(); c.each(function (a) { pfccss.applyRule(a[0],a[1]); });";
    $xml_reponse->script($js);
    
    return $xml_reponse;
  }

  function loadScripts($theme, &$xml_reponse)
  {
    if ($xml_reponse == null) $xml_reponse = new xajaxResponse();

    $c =& pfcGlobalConfig::Instance();

    $js = '';
    
    require_once(dirname(__FILE__)."/../lib/json/JSON.php");
    $json = new Services_JSON();

    $labels_to_load =
      array( "Do you really want to leave this room ?", // _pfc
             "Hide nickname marker", // _pfc
             "Show nickname marker", // _pfc
             "Hide dates and hours", // _pfc
             "Show dates and hours", // _pfc
             "Disconnect", // _pfc
             "Connect", // _pfc
             "Magnify", // _pfc
             "Cut down", // _pfc
             "Hide smiley box", // _pfc
             "Show smiley box", // _pfc
             "Hide online users box", // _pfc
             "Show online users box", // _pfc
             "Please enter your nickname", // _pfc
             "Private message", // _pfc
             "Close this tab", // _pfc
             "Enter your message here", // _pfc
             "Enter your nickname here", // _pfc
             "Bold", // _pfc
             "Italics", // _pfc
             "Underline", // _pfc
             "Delete", // _pfc
             "Mail", // _pfc
             "Color", // _pfc
             "PHP FREE CHAT [powered by phpFreeChat-%s]", // _pfc
             "Enter the text to format", // _pfc
             "Configuration has been rehashed", // _pfc
             "A problem occurs during rehash", // _pfc
             "Choosen nickname is allready used", // _pfc
             "phpfreechat current version is %s", // _pfc
             "Maximum number of joined channels has been reached", // _pfc
             "Maximum number of private chat has been reached", // _pfc
             "Click here to send your message", // _pfc
             "Send", // _pfc
             "You are not allowed to speak to yourself", // _pfc
             "Close", // _pfc
             "Choosen nickname is not allowed", // _pfc
             "Enable sound notifications", // _pfc
             "Disable sound notifications", // _pfc       
             );
    foreach($labels_to_load as $l)
    {
      $js .= "pfc.res.setLabel(".$json->encode($l).",".$json->encode(_pfc2($l)).");\n";
    }
    
    $fileurl_to_load =
      array( 'images/ch.gif',
             'images/pv.gif',
             'images/tab_remove.gif',
             'images/ch-active.gif',
             'images/pv-active.gif',
             'images/user.gif',
             'images/user-me.gif',
             'images/color-on.gif',
             'images/color-off.gif',
             'images/clock-on.gif',
             'images/clock-off.gif',
             'images/logout.gif',
             'images/login.gif',
             'images/maximize.gif',
             'images/minimize.gif',
             'images/smiley-on.gif',
             'images/smiley-off.gif',
             'images/online-on.gif',
             'images/online-off.gif',
             'images/bt_strong.gif',
             'images/bt_em.gif',
             'images/bt_ins.gif',
             'images/bt_del.gif',
             'images/bt_mail.gif',
             'images/bt_color.gif',
             'images/color_transparent.gif',
             'images/close-whoisbox.gif',
             'images/openpv.gif',
             'images/user-admin.gif',
             'images/sound-on.gif',
             'images/sound-off.gif',
             'sound.swf',       
             );
    
    foreach($fileurl_to_load as $f)
    {
      $js .= "pfc.res.setFileUrl(".$json->encode($f).",\"".$c->getFileUrlFromTheme($f)."\");\n";
    }

    foreach($c->smileys as $s_file => $s_str) { 
      for($j = 0; $j<count($s_str) ; $j++) {
        $js .= "pfc.res.setSmiley(".$json->encode($s_str[$j]).",\"".$c->getFileUrlFromTheme($s_file)."\");\n";
      }
    }
    
    $js .= '
pfc.gui.loadSmileyBox();
pfc.gui.loadBBCodeColorList();
pfc.connectListener();
pfc.refreshGUI();
if (pfc_connect_at_startup) pfc.connect_disconnect();
';
    
    $xml_reponse->script($js);
    return $xml_reponse;
  }

  
  function loadInterface($theme = 'default', &$xml_reponse)
  {
    if ($xml_reponse == null) $xml_reponse = new xajaxResponse();

    $c =& pfcGlobalConfig::Instance();
    $c->theme = $theme;
    $u =& pfcUserConfig::Instance();

    $html = '';

    pfcI18N::SwitchOutputEncoding($c->output_encoding);

    $path = $c->getFilePathFromTheme('chat.html.tpl.php');
    $t = new pfcTemplate($path);
    $t->assignObject($u,"u");
    $t->assignObject($c,"c");
    $html .= $t->getOutput();

    pfcI18N::SwitchOutputEncoding();
    
    $xml_reponse->assign("pfc_container", "innerHTML", $html);

    return $xml_reponse;    
  }

  function loadChat($theme = 'default')
  {
    $xml_reponse = new xajaxResponse();

    $this->loadInterface($theme,$xml_reponse);
    $this->loadStyles($theme,$xml_reponse);
    $this->loadScripts($theme,$xml_reponse);
    
    return $xml_reponse;    
  }
  
}

?>
