# ext:Personnel
TYPO3 extension to create contact records and show selected contacts in front end.
Provides customizable vCard download with included image.
## Installation
- Install from TER and add static template to page template.
- Set **config.absRefPrefix** in your page template in order to make base64 images work in vCard (and it shouldn't be just / but a real domain name)

## Custom templates
### In page TsConfig:
**Remove templates from backend drop down list:**
```
TCEFORM.tt_content.tx_personnel_template.removeItems = 1,2
# removes second and third from template dropdown
```
**Change template name and/or add new:**
```
TCEFORM.tt_content.tx_personnel_template.addItems {
  0 = Rename the template
  3 = My Custom Template
}
# renames first template in dropdown and adds one more
```
For front end customizing take a look at the Configuration/TypoScript/ and Resources/Private/Template/ folders.
## Planned
- Make good use of sys categories for showing, example: spoken languages, department etc
- Select records by sys categories
- Proper translation files for BE
## Development and maintenance
* **Tanel Põld**
[Brightside OÜ](https://t3brightside.com/) – TYPO3 development & hosting specialized web agency
### co Developer
* **Nikolay Orlenko**