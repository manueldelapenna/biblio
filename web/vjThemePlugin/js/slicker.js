$(document).ready(function() {
  if($('#sf_admin_filter').is('.hide')){
    $('#sf_admin_filter').hide();
    var isvisible = 1;
  }else{
    $('#sf_admin_filter').show();
    $('#show_filters_toggle').html('<img src="/vjThemePlugin/images/icon/delete.png" alt="" /> Ocultar filtros de busqueda');
    var isvisible = 0;
  }
  
  $('#show_filters_toggle').click(function() {
    $('#sf_admin_filter').slideToggle(400);
    if (isvisible == 0) {
      jQuery(this).html('<img src="/vjThemePlugin/images/icon/delete.png" alt="" /> Ocultar filtros de busqueda');
      isvisible = 1;
    }
    else
    {
      jQuery(this).html('<img src="/vjThemePlugin/images/icon/add.png" alt="" /> Mostrar filtros de busqueda');
      isvisible = 0;
    }
    return false;
  });
});