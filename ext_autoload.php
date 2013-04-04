<?php
$extensionPath = t3lib_extMgm::extPath('restdoc');
$extensionClassesPath = $extensionPath . 'Classes/';
return array(
	'tx_restdoc_utility' => $extensionClassesPath . 'Utility/class.tx_restdoc_utility.php',
	// Compatibility with TYPO3 4.5
	'tx_restdoc_utility_helper' => $extensionClassesPath . 'Utility/Helper.php',
);
?>