<?php if ( ! defined('EXT')) exit('Invalid file request.');

/**
 * SmartDown Images extension model.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Smartdown_images
 */

require_once dirname(__FILE__) .'/smartdown_images_model.php';

class Smartdown_images_extension_model extends Smartdown_images_model {

  /* --------------------------------------------------------------
  * PUBLIC METHODS
  * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @return  void
   */
  public function __construct()
  {
    parent::__construct();
  }


  /**
   * Installs the extension.
   *
   * @access  public
   * @param   string    $class      The extension class.
   * @param   string    $version    The extension version.
   * @param   array     $hooks      The extension hooks.
   * @return  void
   */
  public function install($class, $version, Array $hooks)
  {
    // Guard against nonsense.
    if ( ! is_string($class) OR $class == ''
      OR ! is_string($version) OR $version == ''
      OR ! $hooks
    )
    {
      return;
    }

    $default_hook_data = array(
      'class'     => $class,
      'enabled'   => 'y',
      'hook'      => '',
      'method'    => '',
      'priority'  => '5',
      'settings'  => '',
      'version'   => $version
    );

    foreach ($hooks AS $hook)
    {
      if ( ! is_string($hook) OR $hook == '')
      {
        continue;
      }

      $this->EE->db->insert('extensions', array_merge(
        $default_hook_data,
        array('hook' => $hook, 'method' => 'on_' .$hook)
      ));
    }
  }


  /**
   * Uninstalls the extension.
   *
   * @access    public
   * @param     string    $class    The extension class.
   * @return    void
   */
  public function uninstall($class)
  {
    if ( ! is_string($class) OR $class == '')
    {
      return;
    }

    $this->EE->db->delete('extensions', array('class' => $class));
  }


  /**
   * Updates the extension.
   *
   * @access  public
   * @param   string    $class        The extension class.
   * @param   string    $installed    The installed version.
   * @param   string    $package      The package version.
   * @return  bool
   */
  public function update($class, $installed, $package)
  {
    // Can't do anything without valid data.
    if ( ! is_string($class) OR $class == ''
      OR ! is_string($installed) OR $installed == ''
      OR ! is_string($package) OR $package == ''
    )
    {
      return FALSE;
    }

    // Up to date?
    if (version_compare($installed, $package, '>='))
    {
      return FALSE;
    }

    // Update the version number in the database.
    $this->EE->db->update('extensions',
      array('version' => $package), array('class' => $class));

    return TRUE;
  }


}


/* End of file      : smartdown_images_extension_model.php */
/* File location    : third_party/smartdown_images/models/smartdown_images_extension_model.php */
