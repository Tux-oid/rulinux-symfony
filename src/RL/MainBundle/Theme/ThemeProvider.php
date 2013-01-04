<?php

namespace RL\MainBundle\Theme;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use RL\MainBundle\Security\User\RLUserInterface;

/**
 * RL\MainBundle\Theme\ThemeProvider
 *
 * @author Ax-xa-xa
 * @author Tux-oid
 */
class ThemeProvider implements ThemeProviderInterface
{
    private $context;
    private $httpKernel;
    private $doctrine;

    public function __construct(SecurityContextInterface $context, $kernel, $doctrine)
    {
        $this->context = $context;
        $this->doctrine = $doctrine;
        $this->httpKernel = $kernel;
    }

    public function getTemplate($bundleName, $templateName)
    {
        $theme = $this->getTheme();
        $defaultTheme = $this->doctrine->getRepository('RLMainBundle:Theme')->findOneByName('Ubertechno');
        $tpl = '@RLMainBundle/Resources/views/' . $theme->getDirectory() . '/' . $bundleName . '_' . $templateName;
        try {
            $this->httpKernel->locateResource($tpl);
        } catch(\Exception $e) {
            $tpl = '@RLMainBundle/Resources/views/' . $defaultTheme->getDirectory(
            ) . '/' . $bundleName . '_' . $templateName;
            try {
                $this->httpKernel->locateResource($tpl);
            } catch(\Exception $e) {
                $tpl = '@' . $bundleName . '/Resources/views/Default/' . $bundleName . '_' . $templateName;
                try {
                    $this->httpKernel->locateResource($tpl);
                } catch(\Exception $e) {
                    throw new \Exception('Template not found');
                }

                return $bundleName . ':Default';
            }

            return $defaultTheme->getPath();
        }

        return $theme->getPath();
    }

    public function getTheme()
    {
        $theme = $this->doctrine->getRepository('RLMainBundle:Theme')->findOneByName('Ubertechno');
        if (isset($theme)) {
            $token = $this->context->getToken();
            if (isset($token)) {
                $user = $token->getUser();
                if ($user instanceof RLUserInterface) {
                    $userTheme = $user->getTheme();
                    if (isset($userTheme)) {
                        $theme = $userTheme;
                    }
                }
            }
        }

        return $theme;
    }

    public function getPath($bundleName, $templateName)
    {
        $theme = $this->getTemplate($bundleName, $templateName);

        return $theme . ":" . $bundleName . '_' . $templateName;
    }
}
