<?php

/**
 * PluginaBlogItem form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogItemFormFilter extends BaseaBlogItemFormFilter
{
  public function configure()
  {
    //$this->widgetSchema->setLabel('editors_list', 'Edited By');
    //$this->widgetSchema->setLabel('user_id', 'By');
  }
  
	public function getTagChoices()
	{
		if(isset($this->tags))
		  return $this->tags;
		$this->tags = TagTable::getAllTagNameWithCount(null, array('model' => $this->getModelName()));
		foreach($this->tags as $key => &$tag)
		{
			$tag = $key;
		}
		return $this->tags;
	}
	
  public function setup()
  {
    $this->fields = $this->getFields();
		parent::setup();
		
		$this->setWidget('tags_list', new sfWidgetFormChoice(
		  array(
			  'choices' => $this->getTagChoices(),
				'multiple' => true,
				'expanded' => false
			)
		));
		
		$this->setValidator('tags_list', new sfValidatorChoice(
		  array(
			  'choices' => $this->getTagChoices(),
				'multiple' => true,
				'required' => false
			)
		));

     $this->setWidget('status', new sfWidgetFormChoice(array('choices' => array('' => '', 'draft' => 'draft', 'published' => 'published'))));
    $this->setValidator('status', new sfValidatorChoice(array('required' => false, 'choices' => array('draft' => 'draft', 'published' => 'published'))));
  }
  
  public function getAppliedFilters()
  {
    $values = $this->processValues($this->getDefaults());
    $fields = $this->getFields();
    
    $names = array_merge($fields, array_diff(array_keys($this->validatorSchema->getFields()), array_keys($fields)));
    $fields = array_merge($fields, array_combine($names, array_fill(0, count($names), null)));
    
    $appliedValues = array();
    
    foreach ($fields as $field => $type)
    {
      if (!isset($values[$field]) || null === $values[$field] || '' === $values[$field] || $field == $this->getCSRFFieldName())
      {
        continue;
      }
      
      $method = sprintf('get%sValue', self::camelize($this->getFieldName($field)));
      if (method_exists($this, $method))
      {
        $value = $this->$method($field, $values[$field]);
        if($value) $appliedValues[$field] = $value; 
      }
      else if (null != $type)
      {
        $method = sprintf('get%sValue', $type);
        if (method_exists($this, $method = sprintf('get%sValue', $type)))
        {
          $value = $this->$method($field, $values[$field]);
          if($value) $appliedValues[$field] = $value; 
        }
        
      }
    }
    return $appliedValues; 
  }
  
  protected function getManyKeyValue($field, $values)
  {
    return $this->getForeignKeyValue($field, $values);
  }
  
  protected function getForeignKeyValue($field, $values)
  {
    $appliedValues = array();
    $choices = $this[$field]->getWidget()->getChoices();
    if(is_array($values))
    {
      foreach($values as $value)
      {
        $appliedValues[] = $choices[$value]; 
      }
    }
    else
    {
      $appliedValues[] = $choices[$values];
    }
    return $appliedValues;
  }
  
  protected function getNumberValue($field, $values)
  {
    if(is_array($values) && isset($values['text']) && '' !== $values['text'])
    {
      return $values['text'];
    }
  }
	
	protected function getTextValue($field, $values)
  {
    if(is_array($values) && isset($values['text']) && '' !== $values['text'])
    {
      return $values['text'];
    }
  }
  
  protected function getEnumValue($field, $value)
  {
    return array($value);
  }
  
  protected function getBooleanValue($field, $value)
  {
    if(is_array($value))
    {
      $value = current($value);
    }
    $choices = $this->getWidget($field)->getChoices();
    return array($choices[$value]);
  }
	
	public function addTagsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }
    else
    {
      $values = array_keys($values);
    }

    if (!count($values))
    {
      return;
    }

    $ids = Doctrine::getTable('tagging')->createQuery()
		  ->select('taggable_id')
			->leftJoin('tagging.Tag tag')
		  ->where('taggable_model = ?', $this->getModelName())
			->andWhereIn('tag.name', $values)
			->groupBy('taggable_id')
			->execute(array(), Doctrine::HYDRATE_SCALAR);
    
		$ids = array_map(create_function('$i', 'return $i["tagging_taggable_id"];'), $ids);

		if (empty($ids))
    {
      $query->where('false');
    }
    else
    {
      $query->whereIn($query->getRootAlias() . '.id', $ids);
    }
  }
  
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['tags_list'] = 'ManyKey';
    
    return $fields;
  }
}
