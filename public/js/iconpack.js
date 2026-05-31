$(document).ready(function(){
  $("#iconpack").select2({
   templateResult: formatState
  });
 });
 
 function formatState (state) {
  if (!state.id) { return state.text; }
  var $state = $(
   '<span ><img sytle="display: inline-block;padding:3px;" src="https://admin.ebitans.com/assets/images/icon/' + state.element.value.toLowerCase() +'" width="20px"/> ' + state.text + '</span>'
  );
  return $state;
 }