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
    public function getTheme();

    /**
     * Returns current theme
     */
    public function getTemplate($bundleName, $templateName);

    /**
     * Returns full template names with current layout part.
     *
     * @param string $templateName short template name (without bundle location).
     */
    public function getPath($bundleName, $templateName);
}
