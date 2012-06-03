<?php
/**
 *@author Ax-xa-xa 
 */
namespace RL\SecurityBundle\Security\User;

interface AnonymousUserProviderInterface
{    
    function loadUser($identity, $attributes, &$doctrine);
    
}
