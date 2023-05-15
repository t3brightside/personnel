# Personnel
[![License](https://poser.pugx.org/t3brightside/personnel/license)](LICENSE.txt)
[![Packagist](https://img.shields.io/packagist/v/t3brightside/personnel.svg?style=flat)](https://packagist.org/packages/t3brightside/personnel)
[![Downloads](https://poser.pugx.org/t3brightside/personnel/downloads)](https://packagist.org/packages/t3brightside/personnel)
[![Brightside](https://img.shields.io/badge/by-t3brightside.com-orange.svg?style=flat)](https://t3brightside.com)

**TYPO3 CMS extension for personnel list with vCard support.**
List of people from pages or individual records.
**[Demo](https://microtemplate.t3brightside.com/)**

## Breaking Changes
**v3.0.0** see the [ChangeLog](ChangeLog)

## Features
- List of persons from pages
- List of selected persons
- Sort by
- Pagination with items per page and unique to the content element with [paginatedprocessors](https://github.com/t3brightside/paginatedprocessors)
- Disable from back end: images, vCard link, extra information
- Social links with icons (LinkedIn, Xing, Twitter, GitHub, YouTube, Instagram)
- Base templates for cards, list and table
- Easy to add custom templates

## System requirements
- TYPO3
- fluid_styled_content
- paginatedprocessors

## Installation
- `composer req t3brightside/personnel` or from TYPO3 extension repository **[personnel](https://extensions.typo3.org/extension/personnel/)**
- Add static template
- Include static template for Paginatedprocessors
- Enable page types etc. in "Extension Configuration"

## Usage
- Create personnel records in a Page/Sysfolder
- Add desired content element and point to the Page/Sysfolder or individual records

## Authors for news records
Quite basic but extendable...
- Enable in Personnel extension configuration
- Add 'Personnel - News author' TypoScript template
- See Configuration/TypoScript/NewsAuthor/setup.typoscript for guidance

### Add custom template
**TypoScript**
Check the constant editor.

**PageTS**
```
TCEFORM.tt_content.tx_personnel_template.addItems {
  minilist = Mini List
}
```

**Fluid**
Add new section wheres IF condition determines template name 'minilist' to: _Resources/Private/Templates/Personnel.html_
```xml
<f:if condition="{data.tx_personnel_template} == minilist">
  <f:for each="{personnel}" as="person" iteration="iterator">
    <f:render partial="Minilist" arguments="{_all}"/>
  </f:for>
</f:if>
```
Create new partial: _Resources/Private/Partials/Minilist.html_

### routeEnhancers
For the pagination routing check [t3brightside/paginatedprocessors](https://github.com/t3brightside/paginatedprocessors#readme)
```yaml
PersonnelVcard:
  type: Simple
  limitToPages:
  routePath: '/{person}'
  defaults:
    tag: ''
  requirements:
    person: '[1-999]'
  _arguments:
    person: 'person'
  aspects:
    person:
      type: StaticRangeMapper
      start: '1'
      end: '999'
PageTypeSuffix:
  type: PageType
  map:
    vcard.vcf: 888
```

## Development & maintenance
[Brightside OÜ – TYPO3 development and hosting specialised web agency](https://t3brightside.com/)
