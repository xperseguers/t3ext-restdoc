.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _ts-plugin-tx-restdoc-pi1:

plugin.tx_restdoc_pi1
---------------------

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
   defaultFile_                                          :ref:`t3tsref:data-type-string`                                       yes                     "index"
   mode_                                                 :ref:`t3tsref:data-type-string`                                       yes                     *empty*
   rootPage_                                             :ref:`t3tsref:data-type-integer`                                      yes                     *empty*
   showPermanentLink_                                    :ref:`t3tsref:data-type-boolean`                                      yes                     0
   documentStructureMaxDocuments_                        :ref:`t3tsref:data-type-integer`                                      yes                     50
   advertiseSphinx_                                      :ref:`t3tsref:data-type-boolean`                                      yes                     1
   addHeadPagination_                                    :ref:`t3tsref:data-type-boolean`                                      yes                     1
   publishSources_                                       :ref:`t3tsref:data-type-boolean`                                      yes                     1
   baseWrap_                                             :ref:`t3tsref:stdwrap`                                                yes                     *empty*
   ===================================================== ===================================================================== ======================= ==================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

   .. contents::
      :local:
      :depth: 1


.. _ts-plugin-tx-restdoc-pi1-path:

path
""""

:typoscript:`plugin.tx_restdoc_pi1.path =` :ref:`t3tsref:data-type-string`

Path to the root directory of the documentation.


.. _ts-plugin-tx-restdoc-pi1-defaultFile:

defaultFile
"""""""""""

:typoscript:`plugin.tx_restdoc_pi1.defaultFile =` :ref:`t3tsref:data-type-string`

Default file (main file).


.. _ts-plugin-tx-restdoc-pi1-mode:

mode
""""

:typoscript:`plugin.tx_restdoc_pi1.mode =` :ref:`t3tsref:data-type-string`

Either ``BODY``, ``TOC``, ``RECENT``, ``TITLE``, ``QUICK_NAVIGATION``,
``BREADCRUMB``, ``REFERENCES``, ``FILENAME`` or ``SEARCH`` to setup the plugin
from TypoScript.


.. _ts-plugin-tx-restdoc-pi1-rootPage:

rootPage
""""""""

:typoscript:`plugin.tx_restdoc_pi1.rootPage =` :ref:`t3tsref:data-type-integer`

UID of the page showing the documentation. This setting is used when mode_ =
``SEARCH`` to link back to the documentation from search results.


.. _ts-plugin-tx-restdoc-pi1-showPermanentLink:

showPermanentLink
"""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.showPermanentLink =` :ref:`t3tsref:data-type-boolean`

Whether permanent links should be added to each section.


.. _ts-plugin-tx-restdoc-pi1-documentStructureMaxDocuments:

documentStructureMaxDocuments
"""""""""""""""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.documentStructureMaxDocuments =` :ref:`t3tsref:data-type-integer`

Maximal number of documents to be processed at once when generating the
documentation's structure.


.. _ts-plugin-tx-restdoc-pi1-advertiseSphinx:

advertiseSphinx
"""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.advertiseSphinx =` :ref:`t3tsref:data-type-boolean`

Whether header JS block should be generated to advertise Sphinx to plugins such
as Wappalizer_.


.. _ts-plugin-tx-restdoc-pi1-addHeadPagination:

addHeadPagination
"""""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.addHeadPagination =` :ref:`t3tsref:data-type-boolean`

Whether pagination links should be added to the HEAD part. See Google's
`Webmaster Central Blog`_ for additional information.


.. _ts-plugin-tx-restdoc-pi1-publishSources:

publishSources
""""""""""""""

:typoscript:`plugin.tx_restdoc_pi1.publishSources =` :ref:`t3tsref:data-type-boolean`

If active, the sources of your documentation (content of directory ``_sources/``
will be published. Please note that this flag should be set if you want to show
a context of where a search term was found.


.. _ts-plugin-tx-restdoc-pi1-baseWrap:

baseWrap
""""""""

:typoscript:`plugin.tx_restdoc_pi1.baseWrap =` :ref:`t3tsref:stdwrap`

Override the default wrap for the plugin:

.. code-block:: html

   <div class="tx-restdoc-pi1">
       ...
   </div>

.. warning::

   Default wrap is **not** applied when mode_ is either ``TITLE`` or
   ``FILENAME`` but baseWrap will be applied if defined.
