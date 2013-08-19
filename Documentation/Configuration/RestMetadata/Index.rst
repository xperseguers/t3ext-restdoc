.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _ts-rest-metadata:

REST_METADATA
-------------

.. only:: html

	.. contents::
		:local:
		:depth: 1


Properties
^^^^^^^^^^

.. container:: ts-properties

	===================================================== ===================================================================== ======================= ==================
	Property                                              Data type                                                             :ref:`t3tsref:stdwrap`  Default
	===================================================== ===================================================================== ======================= ==================
	path_                                                 :ref:`t3tsref:data-type-string`                                       yes                     *empty*
	`(stdWrap properties)`_                               :ref:`stdWrap property <t3tsref:stdwrap>`
	===================================================== ===================================================================== ======================= ==================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
		:local:
		:depth: 1


.. _ts-rest-metadata-path:

path
""""

:typoscript:`REST_METADATA.path =` :ref:`t3tsref:data-type-string`

Path to the root directory of the documentation.


(stdWrap properties)
""""""""""""""""""""

:typoscript:`REST_METADATA.<stdWrap property> =` :ref:`stdWrap property <t3tsref:stdwrap>`

Available fields:

* shorttitle
* copyright
* project
* version
* release
* sphinx_version
* ...

.. tip::
	See ``globalcontext.json`` for additional fields.

Example
~~~~~~~

.. code-block:: typoscript

	10 = REST_METADATA
	10.field = copyright
