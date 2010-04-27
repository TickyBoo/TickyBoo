<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html" />
    
    <!--link rel='stylesheet' href='style.php' type='text/css' /-->
    <link rel="stylesheet" href="{$_SHOP_theme_css}style.css" />
    
    <!-- Must be included in all templates -->
    {include file="required_header.tpl"}
    <!-- End Required Headers -->
    
    <script type="text/javascript" src="scripts/jquery/jquery.form.js"></script>
    
    <!--[if lte IE 7]>
      <script type="text/javascript" language="javascript">
        DD_roundies.addRule('#header', '4px');
        DD_roundies.addRule('#col1_content ul', '4px', false);
        DD_roundies.addRule('#col3_content', '4px', false);
        DD_roundies.addRule('.left-box-info', '4px', false);
        DD_roundies.addRule('#footer', '4px', false);
      </script>
    <![endif]-->
  
    <title>Pepper Events Ltd Online Sales</title>
    
    <!--[if lte IE 6]> 
      <script src="scripts/fix/minmax.js" type="text/javascript"></script>
    <![endif]-->
    <!--[if lte IE 7]>
      <link href="{$_SHOP_theme_css}patches/layout_fix.css" rel="stylesheet" type="text/css" >
    <![endif]-->
</head>
<body>

  <div class="page_margins">
  
    <div class="page">
    
      <div id="header"><!-- Header -->
        <div class="floatbox">
          <img class="float_left" src="{$_SHOP_themeimages}logo.gif"/>
          <h1 class="center" style="color:#fff; font-size: 200%;">
            <i>Pepper's Online Ticket Sales<br />
              <font style="color:#e00085;">2010</font>
            </i>
          </h1>
        </div>
        <!--div id="topnav">
      			<a href="?setlang=en">[en]</a> Login 
        </div-->
        
      </div>
      
      <div id="main">

        <div id="col1"><!-- LEFT -->
          <div id="col1_content" class="clearfix">
            
            <h3 class="center">Pepper Tickets</h3>
            <ul class="pep-right-nav">
              <li>
                <a href='index.php'>{!home!}</a>
              </li>
              <li>
                <a href='calendar.php'>{!calendar!}</a>
              </li>
              <li>
                <a href='programm.php'>{!program!}</a>
              </li>
            </ul>
            
            <h3 class="center">Pepper Show</h3>  
            <ul class="pepper-show-links">
              <li>
                <a href="http://www.peppershow.com?page=index" class="menuStyle">&gt; Home</a>
              </li>
              <li><a href="http://www.peppershow.com?page=whatsnew" class="menuStyle">&gt; What's New</a></li>
              <li><a href="http://www.peppershow.com?page=show" class="menuStyle">&gt; The Show</a></li>
              <li><a href="http://www.peppershow.com?page=ticket" class="menuStyle">&gt; Tickets</a></li>
              <li><a href="http://www.peppershow.com?page=volunteer" class="menuStyle">&gt; Volunteer</a></li>
              <li><a href="http://www.peppershow.com?page=raffle" class="menuStyle">&gt; Raffle</a></li>
              <li><a href="http://www.peppershow.com?page=silent" class="menuStyle">&gt; Silent Auction</a></li>
              <li><a href="http://www.peppershow.com?page=roadshows" class="menuStyle">&gt; RoadShows</a></li>
              <li><a href="http://www.peppershow.com?page=contacts" class="menuStyle">&gt; Contacts</a></li> 
            </ul>
            
          </div>
        </div>
        
        <div id="col2"><!-- Right -->
          <div id="col2_content" class="clearfix">
            {include file="user_login_block.tpl"} <br />
          
            {include file="cart_resume.tpl"} <br />
          </div>
        </div>
        
        <div id="col3"><!-- Middle -->
          <div id="col3_content" class="clearfix">
            
            <div id="col3_content_2" class="clearfix">
              
              <!-- Message Divs -->
              <div id="error-message" title='{!order_error_message!}' class="ui-state-error ui-corner-all center" style="padding: 1em; margin-top: .7em; display:none;" >
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                  <span id='error-text'>ffff</span>
                </p>
              </div>
              <div id="notice-message" title='{!order_notice_message!}' class="ui-state-highlight ui-corner-all center" style=" padding: 1em; margin-top: .7em; display:none;" >
                <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                  <span id='notice-text'>fff</span>
                </p>
              </div>
              <!-- End Message Divs -->
              
              {include file="Progressbar.tpl" name=$name} 
              
    					{if $name}
      				  <h1>{$name}</h1>
   						{/if}
    					{if $header}
      				  <p>{$header}</p>
   						{/if}
<!-- Start Template -->