<?php

/**
 * sfOrmBreadcrumbsPlugin configuration.
 * 
 * @package     sfOrmBreadcrumbsPlugin
 * @subpackage  config
 * @author      Johannes Tyra
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class sfOrmBreadcrumbsPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if($this->configuration instanceof sfApplicationConfiguration)
    {
    }
  }
}
