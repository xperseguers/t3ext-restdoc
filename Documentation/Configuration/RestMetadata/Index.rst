.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


REST_METADATA
^^^^^^^^^^^^^

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

	Property
		.. _REST-METADATA-path:

		path

	Data type
		string /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Path to the root directory of the documentation.

		Default: *empty*

.. container:: table-row

	Property
		(:ref:`stdWrap <t3tsref:stdwrap>` properties)

	Data type
		string /:ref:`stdWrap <t3tsref:stdwrap>`

	Description
		Available fields:

		* shorttitle
		* copyright
		* project
		* version
		* release
		* sphinx_version
		* *(see* ``globalcontext.json`` *for additional fields)*

		**Example:** ::

			10 = REST_METADATA
			10.field = copyright

.. ###### END~OF~TABLE ######

[tsref:REST_METADATA]
