<?php $i=1 ?>
<?php foreach($a_blog_category->Users as $user): ?>
<?php echo $user ?><?php if($i < count($a_blog_category->Users)): ?>, <?php endif ?>
<?php $i++ ?>
<?php endforeach ?>
