  <!-- Required Header .tpl Start -->
  <link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.7.2.custom.css" media="screen" />

  <link rel="icon" href="favicon.ico" type="image/x-icon" />

  <script type="text/javascript" src="scripts/jquery/jquery-1.4.1.min.js"></script>
  <script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.2.custom.min.js"></script>
  <script type="text/javascript" src="scripts/jquery/jquery.ajaxmanager.js"></script>
  <script type="text/javascript" src="scripts/jquery/jquery.json-2.2.min.js"></script>

  <script type="text/javascript" src="scripts/jquery/jquery.form.js"></script>
  <script type="text/javascript" src="scripts/jquery/jquery.validate.min.js"></script>
  <script type="text/javascript" src="scripts/jquery/jquery.validate.add-methods.js"></script>
  <script type='text/javascript' src='scripts/jquery/jquery.simplemodal-1.3.3.js'></script>
  <script type='text/javascript' src='scripts/jquery/jquery.countdown.pack.js'></script>
  <script type="text/javascript" src="scripts/shop.jquery.forms.js"></script>

  <script type="text/javascript">
  	var lang = new Object();
  	lang.required = '{!mandatory!}';        lang.phone_long = '{!phone_long!}'; lang.phone_short = '{!phone_short!}';
  	lang.fax_long = '{!fax_long!}';         lang.fax_short = '{!fax_short!}';
  	lang.email_valid = '{!email_valid!}';   lang.email_match = '{!email_match!}';
  	lang.pass_short = '{!pass_too_short!}'; lang.pass_match = '{!pass_match!}';
  	lang.not_number = '{!not_number!}';     lang.condition ='{!check_condition!}';
  </script>

  {literal}
  <style type="text/css">
    #simplemodal-overlay {background-color:#ffffff;}
    #simplemodal-container {background-color:#ffffff; border:2px solid #004088; padding:12px;}
    #simplemodal-container a.modalCloseImg {
      background:url(images/unchecked.gif) no-repeat; /* adjust url as required */
      width:25px; height:29px;
      display:inline; z-index:3200;
      position:absolute; top:-15px;
      right:-18px; cursor:pointer;
    }
  </style>

  <script type="text/javascript">

    var showDialog = function(element){
      jQuery.get(jQuery(element).attr('href'),function(data){
        jQuery("#showdialog").html(data);
        jQuery("#showdialog").modal({
          autoResize:true
        });
      });
      return false;
    }

    function BasicPopup(a) {
      showDialog(a);
      /*
      var url = a.href;
      if (win = window.open(url, a.target || "_blank", 'width=640,height=200,left=300,top=300,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0'))
      {
        win.focus();
        win.focus();
        return false;
      }
      */
      return false;
    }
  </script>
  {/literal}
  <!-- Required Header .tpl  end -->