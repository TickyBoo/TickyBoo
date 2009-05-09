 $(document).ready(function(){
 	$('.ajax').click(function () { 
    // Your code here. You should be able to get the href variable and
    // do your ajax request based on it. Something like:
    var url = $(this).attr('href');
    $.ajax({
        type: "GET",
        url: url
    });
    return false; // You need to return false so the link
                  // doesn't actually fire.
});
 	
 });