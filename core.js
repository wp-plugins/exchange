$(document).ready(function(){
$('#exchange').click(function(){	
fx.settings = { from: $('#country_from').val(), to: $('#country_to').val() };
var change = fx.convert($('#money').val());

$('#exchange_total').val(change);
});
});


