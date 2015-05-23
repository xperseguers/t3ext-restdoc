.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _known-problems:

Known problems
==============

- Hook into EXT:realurl is only possible when this extension is registered *after* EXT:realurl (please
  read :forge:`67121` for details). If needed, you will need to manually edit :file:`typo3conf/PackageStates.php` and
  move definition of "restdoc" after the one for "realurl".

- Only FAL LocalStorage has been implemented and tested, meaning code will need to be adapted in order to deal with other
  types of remote storage.

.. note::
	Please use the extension's bug tracker on Forge to report bugs: https://forge.typo3.org/projects/extension-restdoc/issues
