.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _ts-plugin-tx-restdoc-pi1-setup:

plugin.tx_restdoc_pi1.setup
---------------------------

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
	defaultFile_ (*deprecated*)                           :ref:`t3tsref:data-type-string`                                       yes                     *empty*
	`BODY.image.renderObj`_                               :ref:`cObject <t3tsref:cobjects>`                                     no                      *see* `setup.txt`_
	`TOC.renderObj`_                                      :ref:`cObject <t3tsref:cobjects>`                                     no                      *see* `setup.txt`_
	`MASTER_TOC.renderObj`_                               :ref:`cObject <t3tsref:cobjects>`                                     no                      *see* `setup.txt`_
	`RECENT.renderObj`_                                   :ref:`cObject <t3tsref:cobjects>`                                     no                      *see* `setup.txt`_
	`QUICK_NAVIGATION.renderObj`_                         :ref:`cObject <t3tsref:cobjects>`                                     no                      *see* `setup.txt`_
	`BREADCRUMB.renderObj`_                               :ref:`cObject <t3tsref:cobjects>`                                     no                      *see* `setup.txt`_
	`REFERENCES.renderObj`_                               :ref:`cObject <t3tsref:cobjects>`                                     no                      *see* `setup.txt`_
	===================================================== ===================================================================== ======================= ==================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
		:local:
		:depth: 1


.. _ts-plugin-tx-restdoc-pi1-setup-defaultFile:

defaultFile
"""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.defaultFile =` :ref:`t3tsref:data-type-string`

.. admonition:: Deprecated
	:class: admonition warning

	Use :ref:`plugin.tx_restdoc_pi1.defaultFile <ts-plugin-tx-restdoc-pi1-defaultFile>` instead.


.. _ts-plugin-tx-restdoc-pi1-setup-BODY-image:

BODY.image.renderObj
""""""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.BODY.image.renderObj =` :ref:`cObject <t3tsref:cobjects>`

Setup to render the images within the documentation.


.. _ts-plugin-tx-restdoc-pi1-setup-TOC:

TOC.renderObj
"""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.TOC.renderObj =` :ref:`cObject <t3tsref:cobjects>`

Setup to render the table of contents of the documentation.


.. _ts-plugin-tx-restdoc-pi1-setup-MASTER-TOC:

MASTER_TOC.renderObj
""""""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.MASTER_TOC.renderObj =` :ref:`cObject <t3tsref:cobjects>`

Setup to render the master table of contents of the documentation.


.. _ts-plugin-tx-restdoc-pi1-setup-RECENT:

RECENT.renderObj
""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.RECENT.renderObj =` :ref:`cObject <t3tsref:cobjects>`

Setup to render the list of chapters updated recently.


.. _ts-plugin-tx-restdoc-pi1-setup-QUICK-NAVIGATION:

QUICK_NAVIGATION.renderObj
""""""""""""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.QUICK_NAVIGATION.renderObj =` :ref:`cObject <t3tsref:cobjects>`

Setup to render the quick navigation (previous/next links).


.. _ts-plugin-tx-restdoc-pi1-setup-BREADCRUMB:

BREADCRUMB.renderObj
""""""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.BREADCRUMB.renderObj =` :ref:`cObject <t3tsref:cobjects>`

Setup to render the breadcrumb menu.


.. _ts-plugin-tx-restdoc-pi1-setup-REFERENCES:

REFERENCES.renderObj
""""""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.setup.REFERENCES.renderObj =` :ref:`cObject <t3tsref:cobjects>`

Setup to render the list of references.
