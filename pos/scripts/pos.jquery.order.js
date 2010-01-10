var catData = new Object();
var refreshTimer;
var eventData = new Object();

var loadOrder = function(){
  $("#seat-chart").dialog({
    bgiframe: false,autoOpen: false,
    height: 'auto',maxHeight: 400,
    width: 'auto',modal: true,
    buttons: {
      'Close': function() {
        $(this).dialog('close');
      }
    }
  });

  $("#order_action").dialog({
    bgiframe: false,autoOpen: false,
    height: 'auto',width: 'auto',
    modal: true,
    close: function(event, ui) {
      updateEvents();
      refreshOrder();
    }
  });

  $('#cart_table').jqGrid({
    url:'ajax.php?x=cart',
    datatype: 'json',
    mtype: 'POST',
    postData: {"pos":true,"action":"CartInfo"},
    colNames: ['Expire_in','Event','Count','Tickets','Price','Total'],
    colModel :[
        {name:'Expire_in',  index:'Expire_in',  width:100, sortable:false },
        {name:'Event',      index:'Event',      width:240, sortable:false, resizable: false },
        {name:'Count',      index:'Count',      width: 55, sortable:false, resizable: false, align:'right' },
        {name:'Tickets',    index:'Tickets',    width:190, sortable:false, resizable: false                },
        {name:'Price',      index:'Price',      width: 70, sortable:false, resizable: false, align:'right' },
        {name:'Total',      index:'Total',      width:100, sortable:false, resizable: false, align:'right' }],
    altRows: true,
    height: 116,

    hiddengrid : true,
    hoverrows : false,
    footerrow : false,
    gridComplete:  function(){
      $('#cart_table td').addClass('payment_form');
      var data = $('#cart_table').getGridParam("userData");
      $.each(data.handlings,function(index, domElement){
        $(this.index).html(this.value);
      });
      $('#total_price').html(data.total);
      if (data.can_cancel) {
        $('#cancel').show();
      } else {
        $('#cancel').hide();
      }
      if (data.can_order) {
        $('#checkout').show();
      } else {
        $('#checkout').hide();
      }
      bindCartRemove(); // This listens for cart remove button;
    }
  });
  //refreshOrder();
  updateEvents();

    $('#event-from').datepicker({
      minDate:0, changeMonth: true,
      changeYear: true, dateFormat:'yy-mm-dd',
      showButtonPanel: true,
      onSelect: function(dateText, inst) {
         $('#event-from').change();
      }
    });
    $('#event-to').datepicker({
      minDate:0, changeMonth: true,
      changeYear: true, dateFormat:'yy-mm-dd',
      showButtonPanel: true,
      onSelect: function(dateText, inst) {
         $('#event-to').change();
      }
    });
    $('#event-from').change(function(){
      updateEvents();
    });
    $('#event-to').change(function(){
      updateEvents();
    });
    $("#event-id").change(function(){
      eventIdChange();
    });
    $("#event-id").keyup(function(event){
      if(event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40){
        eventIdChange();
      }
    });
   $("#cat-select").change(function(){
      if($("#event-id").val() > 0 && $("#cat-select").val() > 0 ){
         var catId = $("#cat-select").val();
         $("#cat-free-seats").val(catData.categories[catId].free_seats);
         updateSeatChart();
      }
   });

    //Creates a auto refreshing function.
    refreshTimer = setInterval(function(){refreshOrder();}, 120000);
    $("input:radio[name='handling_id']").click(function(){
      refreshOrder();
    });
    $('#no_fee').click(function(){
      refreshOrder();
    });

    //Make sure all add ticket fields are added to this so when clearing selection
    // All fields are reset.
    $('#clear-button').click(function(){
      clearOrder();
    });

  $("#order-form").submit(function(){
    $("#error-message").hide();
    $(this).ajaxSubmit({
      data:{ajax:"yes",action:"addtocart"},
      success: function(html, status){
          $("#error-text").html(html );
          $("#error-message").show();
          setTimeout(function(){$("#error-message").hide();}, 4000);

        if(html.substring(0,2) == '!~~') {
          $("#error-text").html(html.substring(2));
          $("#error-message").show();
          setTimeout(function(){$("#error-message").hide();}, 4000);
        } else {
          refreshOrder(); //Refresh Cart
          refreshCategories(); //Update ticket info (Free tickets etc)
        }
      }
    });
    return false;
   });

   /**
   	* Sends the order information the POS Confirm action in controller/checkout.php.
   	*/
  $("#checkout").click(function(){
    var userdata = {ajax:"yes",pos:"yes",action:"posConfirm"};
    userdata['handling_id'] = $("input:radio[name='handling_id']:checked").val();

    //If user is being passed check its valid
    if(!$('#user_info_none').is(':checked')){
      if(!$('#pos-user-form').valid()){
        $("#error-text").html("Please fill missing fields!");
        $("#error-message").show();
        setTimeout(function(){$("#error-message").hide();}, 40000);
        return;
      }
    }

    if($("input:checkbox[name='no_fee']").is(":checked")){
      userdata['no_fee'] = 1;
    }else{
      userdata['no_fee'] = 0;
    }
    $("#user_data :input").each(function() {
      userdata[$(this).attr("name")] = $(this).val();
    });
    $("#error-message").hide();
    ajaxQManager.add({
      type:      "POST",
      url:      "checkout.php?x=order",
      dataType:   "HTML",
      data:      userdata,
      success:function(html, status){
          $("#error-text").html('xxxx'+ status);
          $("#error-message").show();

        if(html.substring(0,2) == '!~~!') {
          $("#error-text").html(html.substring(2));
          $("#error-message").show();
          setTimeout(function(){$("#error-message").hide();}, 40000);
        } else {
          $("#order_action").html(html);
          $("#order_action").dialog('open');
          bindCheckoutSubmitForm();
        }
      }
    });
    return false;
   });

   $("#cancel").click(function(){
     $("#error-message").hide();
     ajaxQManager.add({
        type:      "POST",
        url:      "checkout.php?x=cancel",
        dataType:   "HTML",
        data:      {pos:"yes",action:"PosCancel"},
        success:function(html, status){
        refreshOrder();
        }
      });
      return false;
   });

}

//End of order startup