.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


plugin.tx_restdoc_pi1
^^^^^^^^^^^^^^^^^^^^^

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-path:

		path

	Data type
		string /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Path to the root directory of the documentation.

		Default: *empty*

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-defaultFile:

		defaultFile

	Data type
		string /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Default file (main file).

		Default: "index"

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-mode:

		mode

	Data type
		string /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Either "BODY", "TOC", "RECENT", "TITLE", "QUICK_NAVIGATION", "BREADCRUMB", "REFERENCES", "FILENAME" or "SEARCH" to setup the plugin from TypoScript.

		Default: *empty*

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-rootPage:

		rootPage

	Data type
		integer /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		UID of the page showing the documentation. This setting is used when mode = SEARCH to link back to the documentation from search results.

		Default: *empty*

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-showPermanentLink:

		showPermanentLink

	Data type
		boolean /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Whether permanent links should be added to each section.

		Default: 0

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-pathSeparator:

		pathSeparator

	Data type
		string /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Separator to be used between directories of the documentation. You may use multiple characters as well.

		Make sure to read http://forge.typo3.org/issues/45560 before using special characters such as \ (backslash) or / (forward slash).

		Default: "|" (vertical bar)

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-fallbackPathSeparator:

		fallbackPathSeparator

	Data type
		string /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Comma-separated list of fallback path separators.

		Default: "\" (backslash)

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-documentStructureMaxDocuments:

		documentStructureMaxDocuments

	Data type
		integer /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Maximal number of documents to be processed at once when generating the documentation's structure (requires EXT:`dd_googlesitemap`_).

		Default: 50

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-advertiseSphinx:

		advertiseSphinx

	Data type
		boolean /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Whether header JS block should be generated to advertise Sphinx to plugins such as Wappalizer_.

		Default: 1

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-addHeadPagination:

		addHeadPagination

	Data type
		boolean /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Whether pagination links should be added to the HEAD part. See Google's `Webmaster Central Blog`_ for additional information.

		Default: 1

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-publishSources:

		publishSources

	Data type
		boolean /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		If active, the sources of your documentation (content of directory ``_sources/`` will be published. Please note that this flag should be set if you want to show a context of where a search term was found.

		Default: 1

.. container:: table-row

	Property
		.. _tx-restdoc-pi1-baseWrap:

		baseWrap

	Data type
		:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Override the default wrap for the plugin::

			<div class="tx-restdoc-pi1">
			    ...
			</div>

		**Note:** default wrap is NOT applied when mode is either TITLE or FILENAME but baseWrap will be applied if defined.

		Default: *empty*

.. ###### END~OF~TABLE ######

[tsref:plugin.tx_restdoc_pi1]
