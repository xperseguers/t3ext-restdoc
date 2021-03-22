.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt
.. include:: Images.txt


Plugin options
--------------

The plugin options let you choose whether the content of the file (BODY), the
table of contents (TOC), the master table of contents (MASTER_TOC), the quick
navigation (QUICK_NAVIGATION) or a list of references (REFERENCES) should be
generated. The table of contents is in fact a contextual menu for the current
chapter, aka a "mini-toc" whereas the quick navigation shows previous/next
links, typically after the content of the file, in a footer.

|plugin_options|

.. tip::

   Screenshot above shows a non-FAL documentation root. However, both syntaxes
   are supported, mainly to ensure backward compatibility:

   - ``fileadmin/path/to/documentation/``
   - ``file:1:/path/to/documentation/``


Search Form
^^^^^^^^^^^

A search form may be generated with option (SEARCH).

.. caution::

   You must load the jQuery JavaScript framework yourself as the search form
   automatically includes a few JavaScript libraries that depend on jQuery.

.. important::

   The search results may show a context where the search term has been found
   within a chapter. Make sure to allow sources to be published
   (TS ``plugin.tx_restdoc_pi1.publishSources``) if you want to take advantage
   of this feature.
