<?php if(count($categories)): ?>
<div class="a-subnav-section categories">
  <h4>Categories</h4>
  <ul class="a-filter-options blog">
  <?php foreach ($categories as $category): ?>
    <li class="a-filter-option"><?php echo link_to($category, aUrl::addParams(($sf_params->get('cat') == $category->getName()) ? 'aEvent/index' : 'aEvent/index?cat='.urlencode($category->getName()), $params['cat']), array(
      'class' => ($category->getName() == $sf_params->get('cat')) ? 'selected' : '', 
    )) ?></li>
  <?php endforeach ?>
  </ul>	
</div>

<hr />
<?php endif ?>

<div class='a-subnav-section range'>
  <h4>Browse by</h4>
  <ul class="a-filter-options blog">
    <li class="a-filter-option"><?php echo link_to('Day', 'aEvent/index?'.http_build_query(($dateRange == 'day') ? $params['nodate'] : $params['day']), array('class' => ($dateRange == 'day') ? 'selected' : '')) ?></li>
    <li class="a-filter-option"><?php echo link_to('Month', 'aEvent/index?'.http_build_query(($dateRange == 'month') ? $params['nodate'] : $params['month']), array('class' => ($dateRange == 'month') ? 'selected' : '')) ?></li>
    <li class="a-filter-option"><?php echo link_to('Year', 'aEvent/index?'.http_build_query(($dateRange == 'year') ? $params['nodate'] : $params['year']), array('class' => ($dateRange == 'year') ? 'selected' : '')) ?></li>
  </ul>
</div>

<hr />

<?php if(count($tags)): ?>
<div class="a-subnav-section tags">  

	<?php if (isset($tag)): ?>
	<h4 class="a-tag-sidebar-title selected-tag">Selected Tag</h4>  
	<ul class="a-blog-selected-tag">
		<li><?php echo link_to($tag, aUrl::addParams('aEvent/index', $params['tag']), array('class' => 'selected', )) ?></li>
  </ul>
	<?php endif ?>
  
  
	<h4 class="a-tag-sidebar-title popular">Popular Tags</h4>  			
	<ul class="a-tag-sidebar-list popular">
		<?php $n=1; foreach ($popular as $tag => $count): ?>
	  <li <?php echo ($n == count($popular) ? 'class="last"':'') ?>>
			<span class="a-tag-sidebar-tag"><?php echo link_to($tag, aUrl::addParams('aEvent/index?tag='.$tag, $params['tag'])) ?></span>
			<span class="a-tag-sidebar-tag-count"><?php echo $count ?></span>
		</li>
		<?php $n++; endforeach ?>
	</ul>

	<br class="c"/>
	<h4 class="a-tag-sidebar-title all-tags">All Tags <span class="a-tag-sidebar-tag-count"><?php echo count($tags) ?></span></h4>
	<ul class="a-tag-sidebar-list all-tags">
		<?php $n=1; foreach ($tags as $tag => $count): ?>
	  <li <?php echo ($n == count($tag) ? 'class="last"':'') ?>>
			<span class="a-tag-sidebar-tag"><?php echo link_to($tag, aUrl::addParams('aEvent/index?tag='.$tag, $params['tag'])) ?></span>
			<span class="a-tag-sidebar-tag-count"><?php echo $count ?></span>
		</li>
		<?php $n++; endforeach ?>
	</ul>
	
</div>

<hr />
<?php endif ?>

<h5><?php echo link_to('RSS Feed',  aUrl::addParams('aEvent/index?feed=rss', $params['tag'], $params['cat'])) ?></h5>

<script type="text/javascript">
$(document).ready(function() {
	$('.a-tag-sidebar-title.all-tags').click(function(){
		$('.a-tag-sidebar-list.all-tags').slideToggle();
		$(this).toggleClass('open');
	});
	
	$('.a-tag-sidebar-title.all-tags').hover(function(){
		$(this).toggleClass('over');
	},
	function(){
		$(this).toggleClass('over');		
	});	
	
	$('a.selected').prepend('<span class="close"></span>')
});	
</script>