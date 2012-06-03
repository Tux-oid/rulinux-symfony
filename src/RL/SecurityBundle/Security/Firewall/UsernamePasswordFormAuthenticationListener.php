<?php
/**
 *@author Ax-xa-xa 
 */
namespace RL\SecurityBundle\Security\Firewall;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener as BaseListener;

/**
 * Extensds UsernamePasswordFormAuthenticationListener to support generated form.
 */
class UsernamePasswordFormAuthenticationListener extends BaseListener
{
	/**
	 * {@inheritdoc}
	 */
	protected function attemptAuthentication(Request $request)
	{
		if(sizeof($request->request) == 1)
		{
			// it may mean that the data from Symfony Form.
			$keys = $request->request->keys();
			$post = $request->request->get($keys[0]);
			$request = $request->duplicate(null, $post);
		}
		return parent::attemptAuthentication($request);
	}
}
