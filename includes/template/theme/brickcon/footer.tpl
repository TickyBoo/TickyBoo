<!--  </tr>
</table> -->
</div>
  {literal}
  <script type="text/javascript">
  	jQuery(document).ready(function(){
      //var msg = ' errors';
      var emsg = '{/literal}{printMsg|escape:'quotes' key='__Warning__' addspan=false}{literal}';
      showErrorMsg(emsg);
      var nmsg = '{/literal}{printMsg|escape:'quotes' key='__Notice__' addspan=false}{literal}';
      showNoticeMsg(nmsg);

    });
    var showErrorMsg = function(msg){
      if(msg) {
        jQuery("#error-text").html(msg);
        jQuery("#error-message").show();
        setTimeout(function(){jQuery("#error-message").hide();}, 10000);
      }
    }
    var showNoticeMsg = function(msg){
      if(msg) {
        jQuery("#notice-text").html(msg);
        jQuery("#notice-message").show();
        setTimeout(function(){jQuery("#notice-message").hide();}, 7000);
      }
    }
  </script>
  {/literal}


  <div class="clear-block clear">
    <div class="meta">
        </div>

      </div>

</div>          <span class="clear"></span>
          <a href="http://www.brickcon.org/rss.xml" class="feed-icon"><img src="{$_SHOP_themeimages}feed.png" alt="Syndicate content" title="Syndicate content" width="16" height="16" /></a>
          <div id="footer"><b>BrickCon 2010 Theme: Tales of the Brick!</b>
</div>
      </div></div></div></div> <!-- /.left-corner, /.right-corner, /#squeeze, /#center -->

              <div id="sidebar-right" class="sidebar">
                    <div id="block-ad-0" class="clear-block block block-ad">

  <h2>Sponsors</h2>

  <div class="content">
<div class="advertisement" id="group-0"><script type="text/javascript" src="http://www.brickcon.org/modules/ad/serve.php?q=15&amp;t=0"></script></div>
</div>
</div>
        </div>

    </div> <!-- /container -->
  </div>
<!-- /layout -->

<div id="store-footer">
    Copyright 2010<br />
    Powered By <a href="http://www.fusionticket.org"> Fusion Ticket</a> - Free Open Source Online Box Office
</div>
<script type="text/javascript" src="/modules/google_analytics/downloadtracker.js"></script>
  </body>
</html>
