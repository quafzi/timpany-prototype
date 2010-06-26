// Ajax Update Blog Form
function aBlogUpdateForm(slug_url, event)
{
	$.ajax({
	  type:'POST',
	  dataType:'text',
	  data:jQuery('#a-admin-form').serialize(),
	  complete:function(xhr, textStatus)
		{			
      if(textStatus == 'success')
      {
			
	      var json = xhr.getResponseHeader('X-Json'); //data is a JSON object, we can handle any updates with it
	      var data = eval('(' + json + ')');
			
	      if (typeof(data.modified.template) != "undefined" ) {
	        aBlogUpdateTemplate(data.template, data.feedback);
	      };

	      if (typeof(data.modified.allow_comments) != "undefined" ) {
	      	aBlogUpdateComments(data.aBlogPost.allow_comments); // Update Comments after ajax
				};

				aBlogPublishBtn(data.aBlogPost.status, slug_url); // Re-set Publish button after ajax
	      aBlogUpdateTitleAndSlug(data.aBlogPost.title, data.aBlogPost.slug); // Update Title and Slug after ajax
				// aBlogUpdateMessage('Saved!', data.aBlogPost.updated_at);
				aBlogTitleMessage('.'+data.aBlogPost.status+'-item', data.time)	;
				aBlogItemTitle();				

				aUI('#a-admin-form');
			
      }
	 	},
	 	url: slug_url
	});
}

// Pressing the Publish Button
function aBlogPublishBtn(status, slug_url)
{
	//todo: use jq to get the action from the Form ID	for the slug_url
	var postStatus = $('#a_blog_item_status');
	var publishButton = $('#a-blog-publish-button');

	if (status == 'published') {
		publishButton.addClass('published');
		aBlogTitleMessage('.published-item'); // We are editing an existing post that is published;
	};

	publishButton.unbind('click').click(function(){
		$(this).blur();

		if (status == 'draft') 
		{
			postStatus.val('published');
			publishButton.addClass('published');			
		}
		else
		{
			postStatus.val('draft');
			publishButton.removeClass('published');			
		};
		
		// If slug_url
		if (typeof(slug_url) != 'undefined') 
		{
			aBlogUpdateForm(slug_url);			
		};
	});			
}

function aBlogGetPostStatus()
{
	var postStatus = $('#a_blog_item_status');
	return postStatus.val();
}


function aBlogItemTitle(slug_url)
{
	// Title Interface 
	// =============================================
	var titleInterface = $('#a-blog-item-title-interface');
	var titlePlaceholder = $('#a-blog-item-title-placeholder');
	var tInput = titleInterface.find('input');
	var tControls = titleInterface.find('ul.a-controls');
	var originalTitle = tInput.val();

	if (originalTitle == '') 
	{ // The blog post has no title -- Focus the input
		tInput.focus();
		titleInterface.addClass('active');
		aBlogTitleMessage('.new-item'); // We are creating a new post so show us that title;
	}
	else
	{
		status = aBlogGetPostStatus();
		if (status == "draft") {
			aBlogTitleMessage('.draft-item');
		};
		if (status == "published") {
			aBlogTitleMessage('.published-item');
		};
	};
	
	// Title: On Change Compare
	// Turned this off for the 1.4 Release
	// tInput.change(function(){
	// 	save();
	// });
	
	tInput.blur(function()
	{ // Check for Empty Title Field			
		if (tInput.val() == '') 
		{ 	
			tInput.next().show(); 
		}
	});

	tInput.focus(function()
	{	// Always hide the placeholder on focus
		tInput.next().hide(); 
		tInput.select();
	});
		
	tInput.keyup(function(event){
		if (tInput.val().trim() != originalTitle.trim())
		{
			titleInterface.addClass('has-changes');
			tControls.fadeIn();
		}
		if (event.keyCode == '13') {
	    event.preventDefault();
			save();
		}
	});

	titlePlaceholder.mousedown(function()
	{	// If you click the placeholder text 
		// focus the input (Mousedown is faster than click here)
		tInput.focus(); 
	}).hide();
	

	// Title Interface Controls: Save | Cancel
	// =============================================
	tControls.click(function(event)
	{
		event.preventDefault();
		$target = $(event.target);
									
		if ($target.hasClass('a-save'))
		{
			if (tInput.val() == '') 
			{ 	
				tInput.val(originalTitle);
				tControls.hide();							
			}
			if ((tInput.val() != '') && (tInput.val().trim() != originalTitle.trim())) 
			{
				save();
			}										
		}
		
		if ($target.hasClass('a-cancel'))
		{
			tInput.val(originalTitle);
			tControls.hide();
		}
	});

	// Save Blog Title
	function save()
	{
		if (typeof(slug_url) == 'string') 
		{ // If the input is not empty
			// Pass the value to the admin form and update
			$('#a_blog_item_title').val(tInput.val());
			aBlogUpdateForm(slug_url);
		};
		tControls.hide();	
		titleInterface.removeClass('active');		
		tInput.effect('highlight', {}, 2000).blur();	
	}
}

function aBlogItemPermalink(slug_url)
{
	// Permalink Interface  
	// =============================================
	var permalinkInterface = $('#a-blog-item-permalink-interface');
	var pInput = permalinkInterface.find('input');
	var pControls = permalinkInterface.find('ul.a-controls');
	var originalSlug = pInput.val();

	// Permalink: On Focus Listen for Changes
	pInput.focus(function(){
		pInput.select();
		pInput.keyup(function(event){
			if (pInput.val().trim() != originalSlug)
			{
				permalinkInterface.addClass('has-changes');
				pControls.fadeIn();
			}
			if (event.keyCode == '13') {
		    event.preventDefault();
				save();
			}			
		});
	});

	// Permalink Interface Controls: Save | Cancel
	// =============================================
	pControls.click(function(event)
	{
		event.preventDefault();
		$target = $(event.target);
					
		if ($target.hasClass('a-save'))
		{
			if (pInput.val() == '') 
			{ 	
				pInput.val(originalSlug);
				pControls.hide();								
			}
			if ((pInput.val() != '') && (pInput.val().trim() != originalSlug)) 
			{
				save();
			}										
		}
		
		if ($target.hasClass('a-cancel'))
		{
			pInput.val(originalSlug);
			pControls.hide();					
		}
	});
	
	// Save Blog Title
	function save()
	{
		if (pInput.val() != '') 
		{ // If the input is not empty
			// Pass the value to the admin form and update
			$('#a_blog_item_slug').val(pInput.val());
			aBlogUpdateForm(slug_url);
			pControls.hide();
			pInput.effect('highlight', {}, 2000).blur();				
		};		
	}
}

function aBlogUpdateTitle(title)
{ // Update Title Function for Ajax calls when it is returned clean from Apostrophe
	var titleInput = $('#a_blog_item_title_interface');
		
	if (title != null) 
	{
		titleInput.val(title);			
	};
}


function aBlogUpdateSlug(slug)
{ // Update Slug Function for Ajax calls when it is returned clean from Apostrophe
	var permalinkInput = $('#a_blog_item_permalink_interface');
  var slugInput = $('#a_blog_item_slug');

	if (slug != null)
	{
		permalinkInput.val(slug);
     slugInput.val(slug);
	};
}

function aBlogUpdateTitleAndSlug(title, slug)
{ // Update TitleAndSlug Function to save u time :D !
	aBlogUpdateTitle(title);
	aBlogUpdateSlug(slug);
}

function aBlogCheckboxToggle(checkbox)
{ // Toggle any checkbox you want with this one
	checkbox.attr('checked', !checkbox.attr('checked')); 
}

function aBlogUpdateComments(enabled, feedback)
{
	if (enabled)
	{
		$('.section.comments .allow_comments_toggle').addClass('enabled').removeClass('disabled');
	}
	else
	{
		$('.section.comments .allow_comments_toggle').addClass('disabled').removeClass('enabled');		
	}
}

function aBlogUpdateTemplate(template, feedback)
{
	location.reload(true);
}

function aBlogTitleMessage(status, updated_at)
{
	var msgContainer = $('.a-admin-title-sentence');
	
	msgContainer.children().hide();
	
	if (typeof(updated_at) == 'string')
	{
		flashMsg = 	msgContainer.children('.flash-message'); flashMsg.hide();
		var newMessage = flashMsg.clone(); var newMessageText = newMessage.text()+' <b>' + updated_at + '</b>.';
		newMessage.html(newMessageText);
		msgContainer.children(status).children('.flash-message').remove(); // Remove old message		
		msgContainer.children(status).show().append(newMessage); // Append new message
		newMessage.fadeIn('slow');
		// newMessage.fadeIn('slow').fadeTo(4000,1).fadeOut('fast', function(){
		// 	$(this).remove();
		// });
	}
	else
	{
		msgContainer.children(status).show();		
	}
}

function aBlogUpdateMessage(msg, timestamp) // I don't think this is used anymore
{	
	if (typeof(msg) == 'undefined') {
		msg = 'Saved!';
	};
	
	var publishButton = $('#a-blog-publish-button');
	var pUpdate = $('#a-blog-item-update');
	var lastSaved = $('#post-last-saved');
	
	if (pUpdate.data('animating') != 1) {
		pUpdate.data('animating',1).text(msg).fadeIn(100, function(){
			publishButton.children().hide();
			pUpdate.fadeTo(500,1, function(){
				pUpdate.fadeOut(500, function(){
					if (publishButton.hasClass('published')) 
					{
						publishButton.children('.unpublish').fadeIn(100);
					}
					else	
					{
						publishButton.children('.publish').fadeIn(100);					
					}
					lastSaved.find('span').text(timestamp);
					lastSaved.fadeIn(2000, function(){
						lastSaved.fadeTo(3000, 1, function(){
							// lastSaved.fadeOut(); // Fade Out Message after some time
						});
					});					
					pUpdate.data('animating', 0);
				});
			});
		});
	};	
}

function aBlogSendMessage(label, desc)
{	
	// Messages are turned off for now!
	// Send a message to the blog editor confirming a change made via Ajax	
	var mLabel = (label)?label.toString():""; // passed from ajaxAction
	var mDescription = (desc)?desc.toString(): ""; // passed from ajaxAction
	var newMessage = "<dt>"+mLabel+"</dt><dd>"+mDescription+"</dd>";
	var messageContainer = $('#a-blog-item-status-messages');
	messageContainer.append(newMessage).addClass('has-messages');
	messageContainer.children('dt:last').fadeTo(5000,1).fadeOut('slow', function(){ $(this).remove(); }); // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	messageContainer.children('dd:last').fadeTo(5000,1).fadeOut('slow', function(){	$(this).remove(); checkMessageContainer(); });  // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	
	function checkMessageContainer()
	{
		if (!messageContainer.children().length) {
			messageContainer.removeClass('has-messages');
		};
	}
}

function aBlogSetDateRange(a) 
{  
	var b = new Date();  
	var c = new Date(b.getFullYear(), b.getMonth(), b.getDate());  
	if (a.id == 'a_blog_item_end_date_jquery_control') {  
	    if ($('#a_blog_item_start_date_jquery_control').datepicker('getDate') != null) {  
	        c = $('#a_blog_item_start_date_jquery_control').datepicker('getDate');  
	    }
	  	$('#a_blog_item_end_date_jquery_control').datepicker('setDate', c);
	}  
	return {  
		 minDate: c  
	}  	
}

function aPopularTags(tagList, recommendedTags)
{
	// tagList is a jquery object for the input that contains the list of tags
	// recommendedTags is a jquery object that contains .recommended-tags
	recommendedTags.each(function(){
		$(this).click(function(){
			var theTag = $(this).text();
			var tagSeparator = ", ";
			var currentTags = tagList.val();			

				if (!$(this).hasClass('selected'))
				{ 
					if (currentTags == "") { tagSeparator = ""; }; // Remove separator if there are no starting tags
					tagList.val(currentTags += tagSeparator+theTag); 
				}
				else
				{
					newTagList = currentTags.split(',');
					tagPosition = $.inArray(" "+theTag, newTagList);

					if (tagPosition == -1)
					{ // If it can't find the tag in the array, it is the first tag in the list
						tagPosition = 0;
					}

					newTagList.splice(tagPosition,1);
					tagList.val(newTagList.toString());
				}

				$(this).toggleClass('selected');
				aBlogUpdateMulti(); // call the save function after the tags are toggled
			});	
	});
}