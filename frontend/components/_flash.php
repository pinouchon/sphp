<style type="text/css">
    .alert-error, .alert-success, .alert-info { display:none }
</style>

<?php foreach ($flash as $type => $value): ?>
    <div class="alert alert-block alert-<?php echo $type/* error, success or info */ ?>"
         <?php if ($value != '') echo 'style="display:block"' ?>>
        <a class="close" data-dismiss="alert" href="#"><i class="icon-large icon-remove-circle"></i></a>
        <h4 class="alert-heading"><?php echo is_array($value) ? $value['title'] : '' ?></h4>
        <p><?php echo is_array($value) ? $value['body'] : $value ?></p>
    </div>
<?php endforeach; ?>