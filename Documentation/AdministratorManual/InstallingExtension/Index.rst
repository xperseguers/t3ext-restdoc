.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _admin-manual-install:

Installing the extension
------------------------

There are a few steps necessary to install the Sphinx Documentation Viewer Plugin extension. If you have installed other
extensions in the past, you will run into little new here.


.. _admin-manual-install-em:

Install the extension from Extension Manager
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The Sphinx Documentation Viewer Plugin extension can ben installed through the typical TYPO3 installation process using
the Extension Manager.

The Extension Manager will create a new table in your database. This table is used to store references to the chapters
in the context of the plugin and is used to both generate the menu of recent updates in your documentation and to
integrate the structure of your documentation within your website's sitemap, when using this extension together with
dd_googlesitemap_.


.. _admin-manual-install-routing:

Configure Routing (since TYPO3 v9)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

In order to get pretty URL, you are advised to edit file :file:`config/sites/*/config.yaml` and extend it like that:

.. code-block:: yaml

   routeEnhancers:
     Restdoc:
       type: Plugin
       limitToPages: [1]
       routePath: '/{doc}'
       namespace: 'tx_restdoc_pi1'
       requirements:
         doc: '.+'

You should naturally adapt ``limitToPages`` to the pages where the restdoc plugin is located.


.. _admin-manual-install-realurl:

Configure RealURL (TYPO3 v8)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you are using RealURL, the good news is that the Sphinx Documentation Viewer Plugin extension comes with a
configuration for RealURL.

If your configuration is automatically generated (you have a ``typo3conf/realurl_autoconf.php`` file), delete it. It
will be recreated by RealURL the next time you render your page and will integrate our postVarSets configuration.

If you manually tweaked the configuration (you have a ``typo3conf/realurl_conf.php`` file), here is the configuration
we suggest:

.. code-block:: php

	'postVarSets' => array(
	    '_DEFAULT' => array(
	        'chapter' => array(
	            array(
	                'GETvar' => 'tx_restdoc_pi1[doc]',
	            ),
	        ),
	    ),
	),

You may even fully and transparently embed your documentation within the URL, without any "chapter" segment if you
use a forward slash for :ref:`plugin.tx_restdoc_pi1.pathSeparator <ts-plugin-tx-restdoc-pi1-pathSeparator>` and
enable this behaviour in Extension Manager.

.. code-block:: php

	'fixedPostVars' => array(
	    '123' => 'restdoc_advanced_url',
	    '456' => 'restdoc_advanced_url',
	    'restdoc_advanced_url' => array(
	        array(
	            'GETvar' => 'tx_restdoc_pi1[doc]',
	            'userFunc' => 'Causal\\Restdoc\\Hooks\\Realurl->decodeSpURL_getSequence',
	        ),
	    ),
	),

where ``123`` and ``456`` are page uids with a restdoc plugin.

.. hint::
	If you switch from standard configuration with a pipe (``|``) as separator to a forward slash, you probably should
	consider adding the pipe to the comma-separated list of
	:ref:`plugin.tx_restdoc_pi1.fallbackPathSeparator <ts-plugin-tx-restdoc-pi1-fallbackPathSeparator>` to prevent
	invalidating every existing search engine page result.

.. note::
	If you generate links to chapters in TypoScript, you may need to manually replace the encoded forward slash
	(``%2F``) to a non-encoded one (``/``) with :ref:`stdWrap's replacement <t3tsref:replacement>`.
