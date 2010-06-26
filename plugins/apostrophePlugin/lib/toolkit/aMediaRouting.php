<?php

class aMediaRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    
    if (aMediaTools::getOption("routes_register") && in_array('aMedia', sfConfig::get('sf_enabled_modules')))
    {
      // Since the media plugin is now an engine, we need our own
      // catch-all rule for administrative URLs in the media area.
      // Prepending it first means it matches last
      $r->prependRoute('a_media_other', new aRoute('/:action', array(
        'module' => 'aMedia'
      )));

      $r->prependRoute('a_media_image_show', new aRoute('/view/:slug', array(
        'module' => 'aMedia',
        'action' => 'show'
      ), array('slug' => '^' . aTools::getSlugRegexpFragment() . '$')));

      // Allow permalinks for PDF originals
      $r->prependRoute('a_media_image_original', new sfRoute('/uploads/media_items/:slug.original.:format', array(
        'module' => 'aMediaBackend',
        'action' => 'original'
      ), array('slug' => '^' . aTools::getSlugRegexpFragment() . '$', 'format' => '^(jpg|png|gif|pdf)$')));
      
      $route = new sfRoute('/uploads/media_items/:slug.:width.:height.:resizeType.:format', array(
        'module' => 'aMediaBackend',
        'action' => 'image'
      ), array(
        'slug' => '^' . aTools::getSlugRegexpFragment() . '$',
        'width' => '^\d+$',
        'height' => '^\d+$',
        'resizeType' => '^\w$',
        'format' => '^(jpg|png|gif)$'
      ));
      $r->prependRoute('a_media_image', $route);
      
      // What we want:
      // /media   <-- everything
      // /image   <-- media of type image
      // /video   <-- media of type video
      // /tag/tagname <-- media with this tag
      // /image/tag/tagname <-- images with this tag 
      // /video/tag/tagname <-- video with this tag
      // /media?search=blah blah blah  <-- searches are full of
      //                                   dirty URL-unfriendly characters and
      //                                   are traditionally query strings.
      
      $r->prependRoute('a_media_index', new aRoute('/', array(
        'module' => 'aMedia', 
        'action' => 'index'
      )));
      
      $r->prependRoute('a_media_index_type', new aRoute('/:type', array(
        'module' => 'aMedia',
        'action' => 'index'
      ), array('type' => '(image|video)')));
      
      $r->prependRoute('a_media_index_category', new aRoute('/category/:category', array(
        'module' => 'aMedia',
        'action' => 'index'
      ), array('category' => '.*')));
      
      $r->prependRoute('a_media_index_tag', new aRoute('/tag/:tag', array(
        'module' => 'aMedia',
        'action' => 'index'
      ), array('tag' => '.*')));

      $r->prependRoute('a_media_select', new aRoute('/select', array(
        'class' => 'aRoute',
        'module' => 'aMedia',
        'action' => 'select'
      )));
      
      $r->prependRoute('a_media_info', new sfRoute('/info', array(
        'module' => 'aMediaBackend',
        'action' => 'info'
      )));
      
      $r->prependRoute('a_media_tags', new sfRoute('/tags', array(
        'module' => 'aMediaBackend',
        'action' => 'tags'
      )));
      
      $r->prependRoute('a_media_upload_images', new aRoute('/uploadImages', array(
        'module' => 'aMedia',
        'action' => 'uploadImages'
      )));
      
      $r->prependRoute('a_media_edit_images', new aRoute('/editImages', array(
        'module' => 'aMedia',
        'action' => 'editImages'
      )));
      
      $r->prependRoute('a_media_new_video', new aRoute('/newVideo', array(
        'module' => 'aMedia',
        'action' => 'newVideo'
      )));
      
      $r->prependRoute('a_media_edit_video', new aRoute('/editVideo', array(
        'module' => 'aMedia',
        'action' => 'editVideo'
      )));
    }
  }
}
