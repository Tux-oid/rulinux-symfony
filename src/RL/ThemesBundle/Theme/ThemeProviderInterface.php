<?php
/**
 *@author Ax-xa-xa 
 */
namespace RL\ThemesBundle\Theme;

/**
 * Itnterface for page layout provider.
 */
interface ThemeProviderInterface
{
    /**
     * Returns current theme name
     */
    function getName();

    /**
     * Returns current theme
     */
    function getTheme($bundleName, $templateName);
    
    /**
     * Returns full template names with current layout part.
     * 
     * @param string $templateName short template name (without bundle location).
     */
    function getPath($bundleName, $templateName);
}
