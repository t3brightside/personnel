# by t3brightside.com

vCardOrgName = TEXT
vCardOrgName.value = {$personnel.vCard.CompanyName}

vCardURL = TEXT
vCardURL.data = getEnv:HTTP_HOST

page.includeCSS.personnel = {$personnel.stylesPath}

tt_content.personnel_default =< lib.contentElement
tt_content.personnel_default.templateRootPaths.200 = {$personnel.templateRootPaths}
tt_content.personnel_default.templateName = Person

tt_content.personnel_selected =< tt_content.personnel_default
tt_content.personnel_selected {
  dataProcessing.10 = Brightside\Personnel\DataProcessing\DatabaseCustomQueryProcessor
  dataProcessing.10 {
    if.isTrue.field = tx_personnel
    table = tx_personnel_domain_model_person
    pidInList = 0
    uidInList.data = field:tx_personnel
    as = personnel
		dataProcessing {
      10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
      10.references.fieldName = images
		}
  }
}
tt_content.personnel_frompages =< tt_content.personnel_default
tt_content.personnel_frompages {
	dataProcessing.10 = Brightside\Personnel\DataProcessing\DatabaseCustomQueryProcessor
	dataProcessing.10 {
    table = tx_personnel_domain_model_person
    pidInList.data = field:pages
    as = personnel
  	dataProcessing {
      10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
      10.references.fieldName = images
    }
	}
}

personnel_vCard = PAGE
personnel_vCard  {
  typeNum = 888
  config {
    disableAllHeaderCode = 1
    xhtml_cleaning = 0
    debug = 0
    disableCharsetHeader = 1
  }
	10 = FLUIDTEMPLATE
	10 {
		templateName = Vcard
		templateRootPaths.10 = {$personnel.vCard.templateRootPaths}
		dataProcessing.10 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
	  dataProcessing.10 {
      table = tx_personnel_domain_model_person
      pidInList = 0
      uidInList.data = GP:person
	    as = person
    	dataProcessing {
      	10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
				10.references.fieldName = images
			}
		}
	}
}
