# Personnel
**TYPO3 CMS extension for personnel list with vCard support.**

## System requirements

- TYPO3 8.7 LTS – 9.*
- fluid_styled_content

## Features
- List of persons from pages
- List of selected persons
- Sort by
- Easy to add custom templates

## Installation
- Install from TER: **personnel** or Composer: **t3brightside/personnel**
- Add static template
- Set **config.absRefPrefix** in your page template in order to make base64 images work in vCard (and it shouldn't be just / but a real domain name)

## Admin

### Add custom template

**PageTS**

Add new template number '2' and name it:
```typoscript
TCEFORM.tt_content.tx_personnel_template.addItems {
  2 = My New Template
}
```

**TypoScript**

Change constants:
```typoscript
personnel.styles = EXT:personnel/Resources/Public/Styles/personnel.css
personnel.templateRootPaths = EXT:personnel/Resources/Private/Templates/
personnel.partialRootPaths = EXT:personnel/Resources/Private/Partials/
personnel.vCard.templateRootPaths = EXT:personnel/Resources/Private/Templates/
personnel.vCard.CompanyName = Example Company Ltd.

```

**Fluid**

Add new section wheres IF condition determines template nr '2' to: _Resources/Private/Templates/Personnel.html_
```html
<f:if condition="{data.tx_personnel_template} == 2">
  <f:for each="{personnel}" as="person" iteration="iterator">
    <f:render partial="MyNewTemplate" arguments="{_all}"/>
  </f:for>
</f:if>
```
Create new partial: _Resources/Private/Partials/MyNewTemplate.html_

## Development and maintenance
[Brightside OÜ](https://t3brightside.com/) – TYPO3 development and hosting specialised web agency
