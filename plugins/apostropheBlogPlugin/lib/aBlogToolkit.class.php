<?php

class aBlogToolkit {

  public static function isFilterSet($filter, $name)
  {
    $value = aBlogToolkit::getFilterFieldValue($filter, $name);
    if($value === null || $value == '')
    {
      return false;
    }
    if(is_array($name) && count($name) > 1)
    {
      return false;
    }
   
    return true;
  }
  
  public static function getFilterFieldValue($filter, $name)
  {
    $field = $filter[$name];
    $value = $field->getValue();
    $types = $filter->getFields();
    $type = $types[$field->getName()];
    switch($type){
      case 'Enum':
        return $value;
      case 'Boolean':
        return aBlogToolkit::getValueForId($field, $value);
      case 'ForeignKey':
      case 'ManyKey':
        if(is_array($value))
        {
          $values = array();
          foreach($value as $v) $values[] = aBlogToolkit::getValueForId($field, $v);
        }
        else
        {
          $values = aBlogToolkit::getValueForId($field, $value);
        }
        return $values;
      case 'Text':
      case 'Number':
        return $value['text'];  
    }
    
  }
  
  public static function getValueForId($field, $id)
  {
    if(is_null($id)) return null;
    $choices = $field->getWidget()->getChoices();
    return $choices[$id];
  }
}