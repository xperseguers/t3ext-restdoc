.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


Usage with EXT:bootstrap_package
--------------------------------

This section describes how to extend the default page TypoScript from the
`TYPO3 extension bootstrap_package <https://extensions.typo3.org/extension/bootstrap_package/>`_
in order to extend the breadcrumb menu generated as a ``FLUIDTEMPLATE``
dataProcessor and append the breadcrumb menu from the documentation itself.

You may naturally inspire from this even if you are not using the
``bootstrap_package`` extension.

The idea is to create an extension template on the page where you put the
Sphinx Documentation Viewer Plugin:

.. code-block:: typoscript

   # Needed to initialize the plugin without actually outputing anything and
   # be able to generate a full breadcrumb menu as dataProcessing (see below)
   page.1 < plugin.tx_restdoc_pi1
   page.1 {
     baseWrap >
     baseWrap.wrap = |
   }

   # Replace the standard breadcrumb menu from bootstrap_package with our own
   # one, containing the full rootline and documentation's breadcrumb menu
   page.10.dataProcessing.30 {
     special = userfunction
     special.userFunc = Causal\Restdoc\Controller\Pi1\Pi1Controller->makeMenuArray
     special.userFunc.type = rootline_breadcrumb
   }
