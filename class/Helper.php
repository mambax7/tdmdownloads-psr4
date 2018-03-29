<?php namespace XoopsModules\Tdmdownloads;

/*
     You may not change or alter any portion of this comment or credits
     of supporting developers from this source code or any supporting source code
     which is considered copyrighted (c) material of the original comment or credit authors.

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    */
/**
 * xoalbum module for xoops
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GPL 2.0 or later
 * @package         xoalbum
 * @since           2.0.0
 * @author          XOOPS Development Team <name@site.com> - <https://xoops.org>
 */

//defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Helper
 */
class Helper extends \Xmf\Module\Helper
{
    public $debug;

    /**
     * Constructor
     *
     * @param bool $debug
     */

    protected function __construct($debug = false)
    {
        $this->debug   = $debug;
       $moduleDirName = basename(dirname(__DIR__));
       parent::__construct($moduleDirName);
    }

    /**
     * Get instance
     * @param bool $debug
     * @return \XoopsModules\Tdmdownloads\Helper
     */
    public static function getInstance($debug = false)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }

        return $instance;
    }

    /**
     * Get modules
     *
     * @param array $dirnames
     * @param null  $otherCriteria
     * @param bool  $asObj
     *
     * @return array objects
     */
    public function getModules(array $dirnames = null, $otherCriteria = null, $asObj = false)
    {
        // get all dirnames
        $moduleHandler = xoops_getHandler('module');
        $criteria      = new \CriteriaCompo();
        if (count($dirnames) > 0) {
            foreach ($dirnames as $mDir) {
                $criteria->add(new \Criteria('dirname', $mDir), 'OR');
            }
        }
        if (!empty($otherCriteria)) {
            $criteria->add($otherCriteria);
        }
        $criteria->add(new \Criteria('isactive', 1), 'AND');
        $modules = $moduleHandler->getObjects($criteria, true);
        if ($asObj) {
            return $modules;
        }
        $dirs['system-root'] = _YOURHOME;
        foreach ($modules as $module) {
            $dirs[$module->dirname()] = $module->name();
        }

        return $dirs;
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|\XoopsObjectHandler|\XoopsPersistableObjectHandler
     */

    public function getHandler($name)
    {
        $ret   = false;
        $db    = \XoopsDatabaseFactory::getDatabaseConnection();
        $class = '\\XoopsModules\\' . ucfirst(strtolower(basename(dirname(__DIR__)))) . '\\' . $name . 'Handler';
        $ret   = new $class($db);
        return $ret;
    }
}
