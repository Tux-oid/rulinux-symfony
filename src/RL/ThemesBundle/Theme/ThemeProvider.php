<?php
/**
 * @author Ax-xa-xa 
 * @author Tux-oid
 */

namespace RL\ThemesBundle\Theme;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use RL\SecurityBundle\Security\User\RLUserInterface;

class ThemeProvider implements ThemeProviderInterface
{
	private $themes;
	private $default;
	private $context;
	private $httpKernel;
	public function __construct(SecurityContextInterface $context, $kernel)
	{
		$this->context = $context;
		$this->themes = NULL;
		$this->default = NULL;
		$this->httpKernel = $kernel;
		//FIXME: set themes information located in database
		$this->default = 'Default';
		$this->themes = array("Default" => 'RLThemesBundle:Default', "White" => 'RLThemesBundle:White', "CozyGreen" => 'RLThemesBundle:CozyGreen');
	}
	public function getTheme($bundleName, $templateName)
	{
		$themeName = $this->getName($templateName);
		if(array_key_exists($themeName, $this->themes))
		{
			$tpl = '@RLThemesBundle/Resources/views/'.$themeName.'/'.$bundleName.'_'.$templateName;
			try
			{
				$this->httpKernel->locateResource($tpl);
			}
			catch(\Exception $e)
			{
				$tpl = '@RLThemesBundle/Resources/views/Default/'.$bundleName.'_'.$templateName;
				try
				{
					$this->httpKernel->locateResource($tpl);
				}
				catch(\Exception $e)
				{
					$tpl = '@'.$bundleName.'/Resources/views/Default/'.$bundleName.'_'.$templateName;
					try
					{
						$this->httpKernel->locateResource($tpl);
					}
					catch(\Exception $e)
					{
						throw new \Exception('Template not found');
					}
					return $bundleName.':Default';
				}
				return $this->themes['Default'];
			}
			return $this->themes[$themeName];
		}
		throw \Exception('Theme not found');
	}
	function getName()
	{
		$themeName = $this->default;
		if(isset($this->themes))
		{
			$token = $this->context->getToken();
			if(isset($token))
			{
				$user = $token->getUser();
				if($user instanceof RLUserInterface)
				{
					$themeNameUser = $user->getTheme();
					if(isset($themeNameUser))
					{
						$themeName = $themeNameUser;
					}
				}
			}
		}
		return $themeName;
	}
	function getPath($bundleName, $templateName)
	{
		$theme = $this->getTheme($bundleName, $templateName);
		return $theme.":".$bundleName.'_'.$templateName;
	}
}
