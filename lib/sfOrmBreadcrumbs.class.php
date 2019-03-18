<?php

/**
 * sfOrmBreadcrumbs
 * 
 * @package    sfOrmBreadcrumbsPlugin
 * @subpackage lib
 * @author     Nicolò Pignatelli <info@nicolopignatelli.com>
 */
abstract class sfOrmBreadcrumbs 
{
  protected $config = null;
  protected $module = null;
  protected $action = null; 
  protected $breadcrumbs = array();
  protected $area = array();


  abstract protected function buildBreadcrumb($v);
  
  public function __construct($module, $action, $scope = null)
  {
    $this->module = $module;
    $this->action = $action;
    $this->scope  = $scope;

    if ($this->scope){
      $this->module_scope = sprintf("%s_%s", $this->module, $this->scope);
    } else {
      $this->module_scope = "";
    }

    $this->getConfig();
    $this->buildBreadcrumbs();

    //var_dump($this->area);

    sfContext::getInstance()->getRequest()->setAttribute("area", $this->area);
  }

  public function getConfig()
  {
    if($this->config == null)
    {
      $file = sfContext::getInstance()->getConfiguration()->getConfigCache()->checkConfig('config/breadcrumbs.yml');
      $yml = sfYamlConfigHandler::parseYaml($file);
      sfConfig::add($yml);
      
      $this->config = sfConfig::get('sf_orm_breadcrumbs');
    }
    
    return $this->config;
  }
  
  public function getBreadcrumbs()
  {
    return $this->breadcrumbs;
  }
  
  public function getSeparator()
  {
    $config = $this->getConfig();
    return isset($config['_separator']) ? $config['_separator'] : '>';
  }
  
  protected function buildBreadcrumbs()
  {
    if($this->scope && isset($this->config[$this->module_scope]) && isset($this->config[$this->module_scope][$this->action]))
    {
      $breadcrumbs_struct = $this->config[$this->module_scope][$this->action];
    }
    elseif(isset($this->config[$this->module]) && isset($this->config[$this->module][$this->action]))
    {
      $breadcrumbs_struct = $this->config[$this->module][$this->action];
    }

    if(count($breadcrumbs_struct) > 0)
    {
      foreach($breadcrumbs_struct as $item)
      {
        $this->breadcrumbs[] = $this->buildBreadcrumb($item);
      }

      $this->area = $this->breadcrumbs[0];
    }
    else
    {
      $lost = isset($this->config['_lost']) ? $this->config['_lost'] : 'somewhere...';
      $this->breadcrumbs = array(array('name' => $lost, 'url' => null));

      if(isset($this->config['_root']))
      {
        $this->area = $this->breadcrumbs[0];
      }
    }
    
    if(isset($this->config['_root']))
    {
      array_unshift($this->breadcrumbs, $this->buildBreadcrumb($this->config['_root']));
    }
  }
  
  protected function getCaseForItem($item)
  {
    $case = isset($item['case']) ? $item['case'] : null;
	
    if($case == null)
    {
        $config = $this->getConfig();
        $case = isset($config['_default_case']) ? $config['_default_case'] : null;
    }

    return $case;
  }
  
  protected function switchCase($name, $case)
  {
    switch($case)
    {
      case 'ucfirst':
        $name = ucfirst(mb_strtolower($name,'UTF-8'));
        break;
	
	  case 'lcfirst':
        $name = lcfirst(mb_strtolower($name,'UTF-8'));
        break;
      
	  case 'strtolower':
        $name = mb_strtolower($name,'UTF-8');
        break;
		
	  case 'strtoupper':
        $name = mb_strtoupper($name,'UTF-8');
        break;
		
      case 'ucwords':
        $name = ucwords(mb_strtolower($name,'UTF-8'));
        break;
    }
	
	return $name;
  }
  
}
?>