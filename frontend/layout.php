<!DOCTYPE html>
<html lang="en">
  <head>
    <?= App::template('global#head') ?>
  </head>
  <body class="gebo-fixed">
    <div id="loading_layer" style="display:none"><img old_src="/public/img/ajax_loader.gif" alt="" /></div>
    <?= App::template('global#styleSwitcher') ?>

    <div id="maincontainer" class="clearfix">
      <!-- header -->
      <header>
        <?= App::template('global#header') ?>
        <?= App::template('global#mail') ?>
        <?= App::template('global#tasks') ?>
      </header>

      <!-- main content -->
      <div id="contentwrapper">
        <div class="main_content">

          <?= App::template('global#flash') ?>
          <?= $_html ?>
          <? # App::template('global#dashboard') ?> 

        </div>
      </div>

      <!-- sidebar -->
      <a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
      <?= App::template('global#sidebar') ?>


      <?= App::template('global#jsIncludes') ?>
      <? # App::template('global#dashboardIncludes') ?>

      <script>
        $(document).ready(function() {
          //* show all elements & remove preloader
          //setTimeout('$("html").removeClass("js")', 0);
          $("html").removeClass("js");
        });
      </script>
    </div>
  </body>
</html>