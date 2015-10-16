.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt
.. include:: Images.txt


.. _introduction:

Introduction
============


.. _what-it-does:

What does it do?
----------------

.. only:: latex or missing_sphinxcontrib_youtube

	This extension lets you build documentation projects written with Sphinx_ (the Python Documentation Generator used
	by the TYPO3 documentation team for all official documentation) from within the TYPO3 Backend.
	Watch `5 min tutorial video`_.

.. only:: html and not missing_sphinxcontrib_youtube

	This extension lets you build documentation projects written with Sphinx_ (the Python Documentation Generator used
	by the TYPO3 documentation team for all official documentation) from within the TYPO3 Backend:

	.. youtube:: YeGqHMDT7R8
		:width: 100%

	|

.. _Sphinx: http://sphinx-doc.org/

The name of this extension comes from the underlying markup language used by Sphinx. In fact, Sphinx uses reStructuredText_ (commonly abbreviated as reST) as its markup language.

Sphinx was originally created for the Python documentation and a few features are worth highlighting:

- **Output formats:** HTML, JSON (a derivate from HTML this extension is relying on), LaTeX (for printable PDF versions), plain text, ...

- **Extensive cross-references:** semantic markup and automatic links for citations, glossary terms and similar pieces of information. For instance, the official TYPO3 documentation provides resources to cross-link from your own documentation to virtually any chapter or section of any TYPO3 documentation. Please consult page `Tips and Tricks`_ in the TYPO3 wiki for more information.

- **Hierarchical structure:** easy definition of a document tree, with automatic links to siblings, parents and children

- **Automatic index:** general index of terms used in your documentation

- **Extensions:** the tool lets you extend it with your own modules


And this extension?
^^^^^^^^^^^^^^^^^^^

The "Sphinx way" of publishing a reST documentation to the Web is to generate either a standalone HTML website (or single page) or a PDF. The drawback of using a standalone HTML website is that it is extremely tedious to adapt the base templates provided by Sphinx to your needs to get something visually comparable to your website's design you try to look like.

Fortunately, Sphinx lets you generate your documentation as JSON files which are basically the HTML output without any layout.

This extension uses the JSON content parts and lets you freely place them in your TYPO3 website, where they belong in the overall design (e.g., the table of contents as part of your navigation menu, the documentation itself as main content, ...). The integration of your reST documentation is not only much more effective but is also better from a SEO_ point of view as your documentation is seen as *real content* of your TYPO3 website.


How to start?
^^^^^^^^^^^^^

If you already have a Sphinx documentation project at hand, generate a JSON output with ``make json`` instead of ``make html``, copy the whole output directory to your website, place a restdoc plugin on your page and you're done!

If you are new to Sphinx, reStructuredText and currently write your documentation with one of the common word processors (OpenOffice Writer, MS Word, ...), don't worry! The TYPO3 documentation team and a few other passionate persons are maintaining tutorials and tips and tricks in `the TYPO3 wiki`_.

.. tip::
	Wait! "``make json``"? I'm a writer, not a command line aficionado.

	No Problem! Please have a look at extension `Sphinx Python Documentation Generator and Viewer <https://typo3.org/extensions/repository/view/sphinx>`_.


.. _screenshots:

Screenshots
-----------

|plugin_options|
