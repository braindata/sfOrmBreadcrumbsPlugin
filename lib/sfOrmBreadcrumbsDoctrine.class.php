<?php

/**
 * sfOrmBreadcrumbsDoctrine
 * 
 * @package    sfOrmBreadcrumbsPlugin
 * @subpackage lib
 * @author     Nicolò Pignatelli <info@nicolopignatelli.com>
 */
class sfOrmBreadcrumbsDoctrine extends sfOrmBreadcrumbs
{
  protected function buildBreadcrumb($item)
  {
    $request = sfContext::getInstance()->getRequest();
    $routing = sfContext::getInstance()->getRouting();

    if(isset($item['model']) && $item['model'] == true)
    {
      $object = $request->getAttribute('sf_route')->getObject();
      if(isset($item['subobject'])) {
        $subobject = $object->get($item['subobject']);
        $route_object = $subobject;
      } else {
        $route_object = $object;
      }
      $pattern = '/%(\w+)%/';
      preg_match_all($pattern, $item['name'], $matches);

      if(!empty($matches)) {
        $replaces = array();

        foreach($matches[1] as $idx => $match) {
          if(method_exists($route_object, $match)) {
            $replaces[$idx] = $route_object->$match();
          } else {
            try {
              $replaces[$idx] = $route_object->get($match);
            } catch (Exception $e) {
              $replaces[$idx] = $matches[0][$idx];
            }
          }
        }
      }
      //var_dump($matches, $replaces); die;
      $name = !empty($matches) ? str_replace($matches[0], $replaces, $item['name']) : $item['name'];
      $breadcrumb = array('name' => $name, 'url' => isset($item['route']) ? $routing->generate($item['route'], $route_object) : null);
    }
    else
    {
      $url = isset($item['route']) ? $routing->generate($item['route']) : null;
      $breadcrumb = array('name' => $item['name'], 'url' => $url);
    }

	$case = $this->getCaseForItem($item);
	$breadcrumb['name'] = $this->switchCase($breadcrumb['name'], $case);

    return $breadcrumb;
  }
}
?>