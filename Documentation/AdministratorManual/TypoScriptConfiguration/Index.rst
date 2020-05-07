.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


TypoScript configuration
------------------------

In order for this extension to be usable, make sure to include the static template that is provided with this extension.

The TypoScript configuration should be generic enough to let you fine tune the rendering process to fit your needs. As
an example, for a project I changed the rendering of the images to:

- Add an overlay shown when the mouse enters pictures with the ALT label

- Add a fancybox (lightbox) for the image whenever it got resized, but not when the image was not resized (does not make
  sense to show a lightbox with the very same image...

- Do not do those changes for small inline images (say smaller than 100px width)

This is just a matter of playing with TypoScript, something you should love anyway as a TYPO3 integrator. Please note
that the extension comes with a directory containing `TypoScript snippets`_.

.. tip::
	Need some ideas of what is feasible? Please have a look at a `post of mine in Google+`_.


Available modes for the plugin
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

When integrating the plugin with TypoScript, additional modes of operations are available. These are:

TOC
	Generates a table of contents

MASTER_TOC
	Generates the master table of contents

RECENT
	Renders a list of chapters updated recently

BODY
	Generates the documentation itself

TITLE
	Returns the current chapter's title

QUICK_NAVIGATION
	Generates a quick navigation with "previous", "next", "home", ...

BREADCRUMB
	Generates a breadcrumb menu for the current chapter

REFERENCES
	Generates a list of references within the whole documentation

FILENAME
	Returns the name of the .fjson being rendered

SEARCH
	Generates a search form


Additional Content Object
^^^^^^^^^^^^^^^^^^^^^^^^^

This extension registers an additional content object to let you access meta-information from your documentation.
Example:

.. code-block:: typoscript

   10 = REST_METADATA
   10 {
       path = {$plugin.tx_restdoc.path}
       field = copyright
       noTrimWrap = |Copyright &copy; ||
   }

Please read next chapter for a list of available fields.
