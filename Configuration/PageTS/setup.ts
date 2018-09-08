TCEFORM.tt_content.tx_personnel_template.addItems {
	0 = Cards
	1 = List
}

mod.wizards.newContentElement.wizardItems.common {
   elements {
      personnel_selected {
         iconIdentifier = mimetypes-x-content-personnel
         title = Selected Persons
         description = Shows selected person records.
         tt_content_defValues {
            CType = personnel_selected
         }
      }
      personnel_frompages {
         iconIdentifier = mimetypes-x-content-personnel
         title = Persons from Page
         description = Shows persons from selected pages or sys folders.
         tt_content_defValues {
            CType = personnel_frompages
         }
      }
   }
   show := addToList(personnel_selected,personnel_frompages)
}
