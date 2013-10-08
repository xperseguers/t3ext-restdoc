.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


renderHook
^^^^^^^^^^

This hook can be used to post-process the general plugin's output before being wrapped by either your custom TypoScript processing or the standard base class:

.. code-block:: html

	<div class="tx-restdoc-pi1">
	    ...
	</div>

Registration
""""""""""""

You should register your class in:

.. code-block:: php

	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['restdoc']['renderHook']

Expected method
"""""""""""""""

Your hook should implement a method ``postProcessOutput()`` of the form

.. code-block:: php

	public function postProcessOutput(array $params) {
	    // Custom code
	}

Parameters
""""""""""

``$params`` is an array with following keys:

mode
	The plugin's mode

documentRoot
	Absolute path to the documentation's root

document
	Relative path to the current document

output
	Reference to the output of the plugin, may thus be changed within your hook

config
	Configuration of the plugin

pObj
	A reference to the current ``pi_restdoc_pi1`` instance
