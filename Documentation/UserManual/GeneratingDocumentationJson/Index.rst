.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Generating the documentation as JSON
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When kickstarting a project, Sphinx creates a Makefile with different output. Generating your documentation is just a matter of running:

.. code-block:: bash

	$ cd /path/to/documentation
	$ make json

The documentation will be created in a ``json`` directory within the ``build`` (or ``_build``) directory. This ``json`` directory is what should be published to your TYPO3 website, typically somewhere within ``fileadmin/``.
