.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _admin-manual-install:

Installing the extension
------------------------

There are a few steps necessary to install the Sphinx Documentation Viewer
Plugin extension. If you have installed other extensions in the past, you will
run into little new here.


.. _admin-manual-install-em:

Install the extension from Extension Manager
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The Sphinx Documentation Viewer Plugin extension can ben installed through the
typical TYPO3 installation process using the Extension Manager.

The Extension Manager will create a new table in your database. This table is
used to store references to the chapters in the context of the plugin and is
used to both generate the menu of recent updates in your documentation and to
integrate the structure of your documentation within your website's sitemap.


.. _admin-manual-install-routing:

Configure Routing
^^^^^^^^^^^^^^^^^

In order to get pretty URL, you are advised to edit file
:file:`config/sites/*/config.yaml` and extend it like that:

.. code-block:: yaml

   routeEnhancers:
     Restdoc:
       type: RestdocPlugin
       limitToPages: [1]

You should naturally adapt ``limitToPages`` to the pages where the restdoc
plugin is located. If you don't, the plugin will determine that dynamically but
this will have a small performance penalty. You may figure out the list by
running this SQL query:

.. code-block:: sql

   SELECT
       DISTINCT pid
   FROM
       tt_content
   WHERE
       CType = 'list'
       AND list_type = 'restdoc_pi1'
       AND sys_language_uid = 0 AND deleted = 0;

.. info::

   This will default to extend your configuration like that:

   .. code-block:: yaml

      routeEnhancers:
        Restdoc:
          type: RestdocPlugin
          limitToPages: [1]
          routePath: '/{doc}'
          requirements:
            doc: '.+'

   Naturally you may override the `routePath` and corresponding requirements to
   fit your own special use case, if needed.
