# Center images that are wider than 100px
plugin.tx_restdoc_pi1.setup.BODY.image.renderObj {
	stdWrap {
		preCObject = TEXT
		preCObject.value = <div style="text-align:center;">
		preCObject.if.value.data = TSFE:lastImageInfo|0
		preCObject.if.isLessThan = 100
		
		postCObject < .preCObject
		postCObject.value = </div>
	}
}
