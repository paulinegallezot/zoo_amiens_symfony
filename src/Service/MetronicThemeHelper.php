<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class MetronicThemeHelper extends AbstractController
{
    /**
     * Variables
     *
     * @var bool
     */
    private $cache;
    private $package;

    public static $modeSwitchEnabled = false;
    public static $modeDefault = 'light';

    public static $direction = 'ltr';

    public static $htmlAttributes = [];
    public static $htmlClasses = [];


    /**
     * Keep page level assets
     *
     * @var array
     */
    public static $javascriptFiles = [];
    public static $cssFiles = [];
    public static $vendorFiles = [];
    public static $token = null;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->package = new Package(new EmptyVersionStrategy());
    }

    public function setCsrfToken($token){
        self::$token = $token;

    }
    public function getCsrfToken()
    {
        return self::$token;
    }


    /**
     * Get product name
     *
     * @return void
     */
    function getName()
    {
    }

    /**
     * Add HTML attributes by scope
     *
     * @param $scope
     * @param $name
     * @param $value
     *
     * @return void
     */
    function addHtmlAttribute($scope, $name, $value)
    {
        self::$htmlAttributes[$scope][$name] = $value;
        // dump(self::$htmlAttributes);
    }

    /**
     * Add multiple HTML attributes by scope
     *
     * @param $scope
     * @param $attributes
     *
     * @return void
     */
    function addHtmlAttributes($scope, $attributes)
    {
        foreach ($attributes as $key => $value) {
            self::$htmlAttributes[$scope][$key] = $value;
        }
    }

    /**
     * Add HTML class by scope
     *
     * @param $scope
     * @param $value
     *
     * @return void
     */
    function addHtmlClass($scope, $value)
    {
        self::$htmlClasses[$scope] = $value;
    }

    /**
     * Print HTML attributes for the HTML template
     *
     * @param $scope
     *
     * @return string
     */
    function printHtmlAttributes($scope)
    {
        $attributes = [];
        if (isset(self::$htmlAttributes[$scope])) {
            foreach (self::$htmlAttributes[$scope] as $key => $value) {
                $attributes[] = sprintf('%s="%s"', $key, $value);
            }
        }

        return join(' ', $attributes);
    }

    /**
     * Print HTML classes for the HTML template
     *
     * @param $scope
     * @param $full
     *
     * @return string
     */
    function printHtmlClasses($scope, $full = true)
    {
        if (empty(self::$htmlClasses)) {
            return '';
        }

        $classes = '';
        if (isset(self::$htmlClasses[$scope])) {
            $classes = self::$htmlClasses[$scope];
        }

        if ($full) {
            return sprintf('class="%s"', $classes);
        }

        return $classes;
    }

    /**
     * Get SVG icon content
     *
     * @param $path
     * @param $classNames
     * @param $folder
     *
     * @return string
     */
    function getSvgIcon($path, $classNames = 'svg-icon')
    {
        $filesystem = new Filesystem();

        $absPath = $this->getParameter('kernel.project_dir').'/public/assets/media/icons/'.$path;
        if ($filesystem->exists($absPath)) {
            return sprintf('<span class="%s">%s</span>', $classNames, file_get_contents($absPath));
        }

        return '';
    }

    function getParameter(string $name): array|bool|string|int|float|\UnitEnum|null
    {
        return parent::getParameter($name);
    }

    /**
     * Set dark mode enabled status
     *
     * @param $flag
     *
     * @return void
     */
    function setModeSwitch($flag)
    {
        self::$modeSwitchEnabled = $flag;
    }

    /**
     * Check dark mode status
     *
     * @return bool
     */
    function isModeSwitchEnabled()
    {
        return self::$modeSwitchEnabled;
    }

    /**
     * Set the mode to dark or light
     *
     * @param $mode
     *
     * @return void
     */
    function setModeDefault($mode)
    {
        self::$modeDefault = $mode;
    }

    /**
     * Get current mode
     *
     * @return string
     */
    function getModeDefault()
    {
        return self::$modeDefault;
    }

    /**
     * Set style direction
     *
     * @param $direction
     *
     * @return void
     */
    function setDirection($direction)
    {
        self::$direction = $direction;
    }

    /**
     * Get style direction
     *
     * @return string
     */
    function getDirection()
    {
        return self::$direction;
    }

    /**
     * Extend CSS file name with RTL or dark mode
     *
     * @param $path
     *
     * @return string
     */
    function extendCssFilename($path)
    {
        if ($this->isRtlDirection()) {
            $path = str_replace('.css', '.rtl.css');
        }

        return $path;
    }

    /**
     * Check if style direction is RTL
     *
     * @return bool
     */
    function isRtlDirection()
    {
        return self::$direction === 'rtl';
    }

    /**
     * Include favicon from settings
     *
     * @return string
     */
    function includeFavicon()
    {
        return sprintf('<link rel="shortcut icon" href="%s" />', $this->asset($this->getParameter('KT_THEME_ASSETS.favicon')));
    }

    function asset($path)
    {
        // if path is an external URL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // check if manifest file exists
        $filesystem = new Filesystem();
        if (!$filesystem->exists('/public/assets/manifest.json')) {
            return $this->package->getUrl(sprintf('/assets/%s', $path));
        }

        return asset($path);
    }

    /**
     * Include the fonts from settings
     *
     * @return string
     */
    function includeFonts()
    {
        $content = '';

        foreach ($this->getParameter('KT_THEME_ASSETS.fonts') as $url) {
            $content .= sprintf('<link rel="stylesheet" href="%s">', $url);
        }

        return $content;
    }

    /**
     * Get the global assets
     *
     * @return array
     */
    function getGlobalAssets($type = 'js')
    {
        // $this->extendCssFilename()
        return $this->getParameter('KT_THEME_ASSETS.'.$type);
    }

    /**
     * Add multiple vendors to the page by name. Refer to settings KT_THEME_VENDORS
     *
     * @param $vendors
     *
     * @return array
     */
    function addVendors($vendors)
    {
        foreach ($vendors as $value) {
            self::$vendorFiles[] = $value;
        }

        return array_unique(self::$vendorFiles);
    }

    /**
     * Add single vendor to the page by name. Refer to settings KT_THEME_VENDORS
     *
     * @param $vendor
     *
     * @return void
     */
    function addVendor($vendor)
    {
        self::$vendorFiles[] = $vendor;
    }

    /**
     * Add custom javascript file to the page
     *
     * @param $file
     *
     * @return void
     */
    function addJavascriptFile($file)
    {
        self::$javascriptFiles[] = $file;
    }

    /**
     * Add custom CSS file to the page
     *
     * @param $file
     *
     * @return void
     */
    function addCssFile($file)
    {
        self::$cssFiles[] = $file;
    }

    /**
     * Get vendor files from settings. Refer to settings KT_THEME_VENDORS
     *
     * @param $type
     *
     * @return array
     */
    function getVendors($type)
    {
        $files = [];
        foreach (self::$vendorFiles as $vendor) {
            $vendors = $this->getParameter('KT_THEME_VENDORS');
            if (isset($vendors[$vendor][$type])) {
                foreach ($vendors[$vendor][$type] as $path) {
                    $files[] = $path;
                }
            }
        }

        return array_unique($files);
    }

    /**
     * Get custom js files from the settings
     *
     * @return array
     */
    function getCustomJs()
    {
        return self::$javascriptFiles;
    }

    /**
     * Get custom css files from the settings
     *
     * @return array
     */
    function getCustomCss()
    {
        return self::$cssFiles;
    }

    /**
     * Get HTML attribute based on the scope
     *
     * @param $scope
     * @param $attribute
     *
     * @return array
     */
    function getHtmlAttribute($scope, $attribute)
    {
        return self::$htmlAttributes[$scope][$attribute] ?? [];
    }

    function image($path)
    {
        return $this->asset(sprintf('media/%s', $path));
    }

    function getIcon($name, $class = '', $type = '')
    {
        $tag = 'i';

        if (empty($type)) {
            $type = $this->getParameter('KT_THEME_ICONS') ?? 'duotone';
        }

        if ($type === 'duotone') {
            $icons = $this->cache->get('duotone-icons', function () {
                return json_decode(file_get_contents($this->getParameter('kernel.project_dir').'/public/icons.json'), true);
            });

            $pathsNumber = $icons['duotone-paths'][$name] ?? 0;

            $output = '<'.$tag.' class="ki-'.$type.' ki-'.$name.(!empty($class) ? " ".$class : '').'">';

            for ($i = 0; $i < $pathsNumber; $i++) {
                $output .= '<'.$tag.' class="path'.($i + 1).'"></'.$tag.'>';
            }

            $output .= '</'.$tag.'>';
        } else {
            $output = '<'.$tag.' class="ki-'.$type.' ki-'.$name.(!empty($class) ? " ".$class : '').'"></'.$tag.'>';
        }

        return $output;
    }
}
