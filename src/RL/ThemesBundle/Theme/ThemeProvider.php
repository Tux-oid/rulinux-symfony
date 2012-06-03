<?php
/**
 *@author Ax-xa-xa 
 */
namespace RL\ThemesBundle\Theme;
use Symfony\Component\Security\Core\SecurityContextInterface;
use RL\SecurityBundle\Security\User\RLUserInterface;

class ThemeProvider implements ThemeProviderInterface
{
	private $themes;
	private $default;
	private $context;
	public function __construct(SecurityContextInterface $context)
	{
		$this->context = $context;
		$this->themes = NULL;
		$this->default = NULL;
		//FIXME: set themes information located in database
		$this->default = 'Default';
		//FIXME: set themes information located in database
		$this->themes = array("Default"=>'RLThemesBundle:Default', "White"=>'RLThemesBundle:White', "default"=>'RLThemesBundle:Default');
	}
	public function getTheme()
	{
		$themeName = $this->getName();
		return $this->themes[$themeName];
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
	function getPath($templateName)
	{
		$theme = $this->getTheme();
		return $theme.":".$templateName;
	}
}
