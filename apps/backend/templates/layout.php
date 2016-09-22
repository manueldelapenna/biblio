<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/biblio/web/images/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
            
            <h1><a href="index.html">cobranza<span class="logo_colour">Biblioteca José Ingenieros</span></a></h1>
            <h2>Sistema de cobranza<?php if ($sf_user->isAuthenticated()){
                 echo " - Ud. está logueado como ". link_to($sf_user->getUserName(), 'cambiarPassword/index');
          }?>            
          </h2>
        </div>
      </div>
      <div id="menubar">
        <div id="menu">
          
            <ul class="menu">
                <?php if ($sf_user->isAuthenticated()){?> 
                <li><?php echo link_to("<span>Inicio</span>","index/index")?></li>
                <li><?php echo link_to('<span>Usuarios</span>', 'sfGuardUser/index')?></li>  
                <li><?php echo link_to("<span>Backup/Restore</span>","backup/index")?></li>
                <li><?php echo link_to("<span>Tablas de referencia</span>","tablasDeReferencia/index")?></li>
                <li><?php echo link_to('<span>Salir</span>','sfGuardAuth/signout')?></li>

            <?php }?>
            </ul>
      </div>
    </div>
    <div id="site_content">
      
       <?php echo $sf_content ?>

    </div>
    <div id="content_footer"></div>
    <div id="footer">
      
    </div>
  </div>
 </div>
  </body>
</html>
