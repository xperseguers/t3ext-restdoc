.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Installing the extension
^^^^^^^^^^^^^^^^^^^^^^^^

There are a few steps necessary to install the reST Documentation Viewer extension. If you have installed other extensions in the past, you will run into little new here.


Install the extension from Extension Manager
""""""""""""""""""""""""""""""""""""""""""""

The reST Documentation Viewer extension can ben installed through the typical TYPO3 installation process using the Extension Manager.

The Extension Manager will create a new table in your database. This table is used to store references to the chapters in the context of the plugin and is used to both generate the menu of recent updates in your documentation and to integrate the structure of your documentation within your website's sitemap, when using this extension together with dd_googlesitemap_.


Configure RealURL
"""""""""""""""""

If you are using RealURL, the good news is that the reST Documentation Viewer extension comes with a configuration for RealURL.

If your configuration is automatically generated (you have a ``typo3conf/realurl_autoconf.php`` file), delete it. It will be recreated by RealURL the next time you render your page and will integrate our postVarSets configuration.

If you manually tweaked the configuration (you have a ``typo3conf/realurl_conf.php`` file), here is the configuration we suggest::

	"postVarSets" => array(
	    "_DEFAULT" => array(
	        "chapter" => array(
	            array(
	                "GETvar" => "tx_restdoc_pi1[doc]",
	            ),
	        ),
	    ),
	),
