# Create the standard footer line for Sphinx documentation
lib.sphinx.footer = REST_METADATA
lib.sphinx.footer {
	path = {$plugin.tx_restdoc.path}

	cObject = COA
	cObject {
		10 = TEXT
		10.field = copyright
		10.noTrimWrap = |&copy; Copyright |.|

		20 = TEXT
		20.field = sphinx_version
		20.noTrimWrap = | Created using <a href="http://sphinx.pocoo.org/">Sphinx</a> |.|
	}
}
