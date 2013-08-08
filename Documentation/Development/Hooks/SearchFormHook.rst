.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


searchFormHook
""""""""""""""

This hook can be used to pre-process the search form.

Registration
~~~~~~~~~~~~

You should register your class in:

.. code-block:: php

	$GLOBALS["TYPO3_CONF_VARS"]["EXTCONF"]["restdoc"]["searchFormHook"]

Expected method
~~~~~~~~~~~~~~~

Your hook should implement a method ``preProcessSEARCH()`` of the form

.. code-block:: php

	public function preProcessSEARCH(array $params) {
	    // Custom code
	}

Parameters
~~~~~~~~~~

``$params`` is an array with following keys:

config
	A reference to the configuration of the search form:

	* **jsLibs:** Array of JavaScript libraries to be loaded (``underscore.js``, ``doctools.js``, ``searchtools.js``)

	* **jsInline:** Inline JavaScript code (loading the index resource)

	* **advertiseSphinx:** Whether to advertise Sphinx (standard inline JavaScript code needed by ``searchtools.js``, thus default to ``TRUE``)

pObj
	A reference to the current ``pi_restdoc_pi1`` instance
