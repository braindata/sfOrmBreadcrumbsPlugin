<?php foreach($breadcrumbs as $key => $breadcrumb): ?>
  <?php if($key == count($breadcrumbs)-1) echo "<li>" ?>
  <?php if($breadcrumb['url'] != null): ?>
    <?php echo link_to(__($breadcrumb['name']), $breadcrumb['url']) ?>
  <?php else: ?>
    <?php echo __($breadcrumb['name']) ?>
  <?php endif ?>
  <?php if($key < count($breadcrumbs)-1): ?> <?php echo '<span class="divider">'.$sf_data->getRaw('separator').'</span>' ?> <?php endif ?>
  <?php if($key == count($breadcrumbs)-1) echo "</li>" ?>
<?php endforeach ?>