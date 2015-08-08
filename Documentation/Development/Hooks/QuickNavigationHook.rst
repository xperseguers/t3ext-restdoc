.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


quickNavigationHook
^^^^^^^^^^^^^^^^^^^

This hook can be used to post-process the quick navigation items.

Registration
""""""""""""

You should register your class in:

.. code-block:: php

	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['restdoc']['quickNavigationHook']

Expected method
"""""""""""""""

Your hook should implement a method ``postProcessQUICK_NAVIGATION()`` of the form

.. code-block:: php

	public function postProcessQUICK_NAVIGATION(array $params)
	{
	    // Custom code
	}

Parameters
""""""""""

``$params`` is an array with following keys:

documentRoot
	Absolute path to the documentation's root

document
	Relative path to the current document

data
	A reference to an array with the various links, may thus be changed within your hook. Available keys:

	* home_title / home_uri / home_uri_absolute
	* previous_title / previous_uri / previous_uri_absolute
	* next_title / next_uri / next_uri_absolute
	* parent_title / parent_uri / parent_uri_absolute
	* index_title / index_uri / index_uri_absolute
	* has_previous / has_next / has_parent / has_index

config
	Configuration of the plugin

pObj
	A reference to the current ``\Causal\Restdoc\Controller\Pi1\Pi1Controller`` instance
