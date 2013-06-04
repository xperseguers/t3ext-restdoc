.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


makeMenuArrayHook
"""""""""""""""""

This hook can be used to post-process the menu entries when generating a menu in TypoScript (general menu, previous/next page, breadcrumb menu, updated chapters).

Registration
~~~~~~~~~~~~

You should register your class in::

	$GLOBALS["TYPO3_CONF_VARS"]["EXTCONF"]["restdoc"]["makeMenuArrayHook"]

Expected method
~~~~~~~~~~~~~~~

Your hook should implement a method ``postProcessTOC()`` of the form

::

	public function postProcessTOC(array $params) {
	    // Custom code
	}

Parameters
~~~~~~~~~~

``$params`` is an array with following keys:

documentRoot
	Absolute path to the documentation's root

document
	Relative path to the current document

data
	A reference to an array of menu entries compatible with the various ``*MENU`` content objects, may thus be changed within your hook

config
	Configuration of the plugin

pObj
	A reference to the current ``pi_restdoc_pi1`` instance
