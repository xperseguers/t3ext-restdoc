.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _development:

Development
===========

This chapter describes some internals of the restdoc extension to let you extend it easily.


.. _development-hooks:

Hooks
-----

.. toctree::
	:maxdepth: 1

	Hooks/RenderHook
	Hooks/MakeMenuArrayHook
	Hooks/QuickNavigationHook
	Hooks/SearchFormHook


.. _development-extending:

In case you want to really extend the output, you may want to take a whole different approach and only take advantage
of the internal Sphinx JSON reader but create the plugin on your own, possibly using Extbase instead of the plugin we
provide.

In that case, be sure to have a look at this project to inspire from: https://github.com/xperseguers/t3ext-docstypo3org
