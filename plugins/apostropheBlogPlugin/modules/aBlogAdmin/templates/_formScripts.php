<script type="text/javascript" charset="utf-8">	
	function aBlogUpdateMulti() { aBlogUpdateForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>'); }
	$(document).ready(function(){
		
	    $('#a-admin-form').change(function(event) {
		    if (!( event.target.className == 'a-multiple-select-input' && event.target.options[0].selected == true || event.target.name == 'add-text' || event.target.name == 'a-ignored' ))
				{
		      aBlogUpdateForm('<?php echo url_for('a_blog_admin_update', $a_blog_post) ?>', event);
				}
	    });

      $('#<?php echo $form['published_at']->renderId() ?>-ui').bind('aTimeUpdated',function(event){
        aBlogUpdateForm('<?php echo url_for('a_blog_admin_update', $a_blog_post) ?>', event);
      });

			// Sidebar Toggle
			// =============================================
	    $('.a-sidebar-toggle').click(function(){
	      $(this).toggleClass('open').next().toggle();
	    })

			// Comments Toggle
			// =============================================
			$('.section.comments a.allow_comments_toggle').click(function(event){
				event.preventDefault();
				aBlogCheckboxToggle($('#a_blog_item_allow_comments'));
				aBlogUpdateForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
			});

			aPopularTags($('#a_blog_item_tags'), $('#blog-tag-list .recommended-tag'));
			aBlogItemTitle('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
			aBlogItemPermalink('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
	    aBlogPublishBtn('<?php echo $a_blog_post->status  ?>','<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
	    aMultipleSelect('#categories-section', { 'choose-one': '<?php echo __('Choose Categories', array(), 'apostrophe_blog') ?>' <?php if($sf_user->hasCredential('admin')): ?>, 'add': '<?php echo __('+ New Category', array(), 'apostrophe_blog') ?>'<?php endif ?>, 'onChange': aBlogUpdateMulti });
	    aMultipleSelect('#editors-section', { 'choose-one': '<?php echo __('Choose Editors', array(), 'apostrophe_blog') ?>','onChange': aBlogUpdateMulti  });    
	 });

	$(window).bind('beforeunload', function() {
	<?php // We want to save the blog post editor when you close the browser window or navigate away from it ?>
		aBlogUpdateForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
	});
</script>