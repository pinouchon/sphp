<style type="text/css">
  .alert-error, .alert-success, .alert-info { display:none }
</style>

<div>
  <?php foreach ($flash as $type => $value): ?>
    <div class="alert alert-<?= $type ?>" <?= empty($value) ? : 'style="display:block"' ?>>
      <a class="close" data-dismiss="alert">×</a>
      <?= $value ?>
    </div>
  <? endforeach ?>
</div>