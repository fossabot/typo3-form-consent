<div align="center">

![Extension icon](Resources/Public/Icons/Extension.svg)

# TYPO3 extension `form_consent`

[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=eliashaeussler_typo3-form-consent&metric=coverage)](https://sonarcloud.io/dashboard?id=eliashaeussler_typo3-form-consent)
[![Tests](https://github.com/eliashaeussler/typo3-form-consent/actions/workflows/tests.yaml/badge.svg)](https://github.com/eliashaeussler/typo3-form-consent/actions/workflows/tests.yaml)
[![CGL](https://github.com/eliashaeussler/typo3-form-consent/actions/workflows/cgl.yaml/badge.svg)](https://github.com/eliashaeussler/typo3-form-consent/actions/workflows/cgl.yaml)
[![Release](https://github.com/eliashaeussler/typo3-form-consent/actions/workflows/release.yaml/badge.svg)](https://github.com/eliashaeussler/typo3-form-consent/actions/workflows/release.yaml)
[![License](http://poser.pugx.org/eliashaeussler/typo3-form-consent/license)](LICENSE.md)\
[![Version](https://shields.io/endpoint?url=https://typo3-badges.dev/badge/form_consent/version/shields)](https://extensions.typo3.org/extension/form_consent)
[![Downloads](https://shields.io/endpoint?url=https://typo3-badges.dev/badge/form_consent/downloads/shields)](https://extensions.typo3.org/extension/form_consent)
[![Extension stability](https://shields.io/endpoint?url=https://typo3-badges.dev/badge/form_consent/stability/shields)](https://extensions.typo3.org/extension/form_consent)
[![TYPO3 badge](https://shields.io/endpoint?url=https://typo3-badges.dev/badge/typo3/shields)](https://typo3.org/)

:package:&nbsp;[Packagist](https://packagist.org/packages/eliashaeussler/typo3-form-consent) |
:hatched_chick:&nbsp;[TYPO3 extension repository](https://extensions.typo3.org/extension/form_consent) |
:floppy_disk:&nbsp;[Repository](https://github.com/eliashaeussler/typo3-form-consent) |
:bug:&nbsp;[Issue tracker](https://github.com/eliashaeussler/typo3-form-consent/issues)

</div>

An extension for TYPO3 CMS that adds double opt-in functionality to
EXT:form. It allows the dynamic adaptation of the entire double opt-in
process using various events. In addition, the extension integrates
seamlessly into TYPO3, for example to delete expired consents in
compliance with the GDPR.

## :rocket: Features

* Custom `Consent` form finisher for EXT:form
* Stores all submitted form data as JSON in database
* System-dependent hash-based validation system (using TYPO3's HMAC functionality)
* Plugin to approve or dismiss a consent
* Possibility to [invoke finishers on consent approval](#invoke-finishers-on-consent-approval)
* Several [events](#events) for better customization
* Scheduler garbage collection task for expired consents
* Dashboard widget for approved, non-approved and dismissed consents
* Compatible with TYPO3 10.4 and 11.5 LTS

## :fire: Installation

```bash
composer require eliashaeussler/typo3-form-consent
```

## :zap: Usage

A new finisher `Consent` is available in the backend form editor.
It saves all submitted form data in the database and sends a
corresponding mail to either approve or dismiss a given consent.

A plugin is required for approval or dismiss of the consent. The
associated page containing the plugin must then be specified in the
finisher settings.

## :open_file_folder: Configuration

Only the TypoScript setup under `EXT:form_consent/Configuration/TypoScript`
needs to be included and the required database changes need to be made.

### TypoScript

The following TypoScript constants are available:

| TypoScript constant | Description | Required | Default |
|---------------------|-------------|----------|---------|
| **`plugin.tx_formconsent.persistence.storagePid`** | Default storage PID for new consents | – | `0` |
| **`plugin.tx_formconsent.view.templateRootPath`** | Path to template root for consent mail and validation plugin | – | – |
| **`plugin.tx_formconsent.view.partialRootPath`** | Path to template partials for consent mail and validation plugin | – | – |
| **`plugin.tx_formconsent.view.layoutRootPath`** | Path to template layouts for consent mail and validation plugin | – | – |

### Finisher options

The following options are available to the `Consent` finisher:

| Finisher option | Description | Required | Default |
|-----------------|-------------|----------|---------|
| **`subject`**   | Mail subject | – | `Approve your consent` |
| **`recipientAddress`** | Recipient e-mail address | :white_check_mark: | – |
| **`recipientName`** | Recipient name | – | – |
| **`senderAddress`** | Sender e-mail address | – | _System default sender e-mail address_ |
| **`senderName`** | Sender name | – | _System default sender name_ |
| **`approvalPeriod`** | Approval period | :white_check_mark: | `86400` (1 day), `0` = unlimited |
| **`showDismissLink`** | Show dismiss link in consent mail | – | `false` |
| **`confirmationPid`** | Confirmation page (contains plugin) | :white_check_mark: | – |
| **`storagePid`** | Storage page | – | `plugin.tx_formconsent.persistence.storagePid` |
| **`templateRootPaths`** | Additional paths to template root | – | – |
| **`partialRootPaths`** | Additional paths to template partials | – | – |
| **`layoutRootPaths`** | Additional paths to template layouts | – | – |

:bulb: **Note:** Template paths that are configured via form finisher
options are only applied to the appropriate form. They are merged
with the default template paths configured via TypoScript.

## :writing_hand: Customization

The lifecycle of the entire consent process can be influenced in several
ways. This leads to high flexibility in customization while maintaining
high stability of the core components.

### Events

PSR-14 events can be used to modify different areas in the consent process.
The following events are available:

* [`ApproveConsentEvent`](Classes/Event/ApproveConsentEvent.php)
* [`DismissConsentEvent`](Classes/Event/DismissConsentEvent.php)
* [`GenerateHashEvent`](Classes/Event/GenerateHashEvent.php)
* [`ModifyConsentEvent`](Classes/Event/ModifyConsentEvent.php)
* [`ModifyConsentMailEvent`](Classes/Event/ModifyConsentMailEvent.php)

### Invoke finishers on consent approval

After a user has given consent, it is often necessary to execute certain
form finishers. For example, to send an admin email or redirect to a
specific page.

To achieve this, after the user gives consent, the originally completed
form is resubmitted. During this resubmission of the form, the selected
finishers can now be overwritten using the `isConsentApproved()` condition
in a form variant.

#### Requirements

The following requirements must be met for the form to be resubmitted:

1. Form variant at the root level of the form must exist
2. Form variant must redefine the finishers used
3. Condition `isConsentApproved()` must exist in the variant

#### Example

The following form variant is stored directly on the root level of the
form definition (that is, your `.form.yaml` file). It specifies the form
finishers to be executed in case of successful approval by the user.

```yaml
variants:
  -
    identifier: post-consent-approval-variant-1
    condition: 'isConsentApproved()'
    finishers:
      -
        identifier: EmailToReceiver
        options:
          # ...
      -
        identifier: Redirect
        options:
          # ...
```

In this example, an admin email would be sent after the consent has been
given and a redirect to the configured confirmation page would take place.

## :gem: Credits

Icons made by [Google](https://www.flaticon.com/authors/google) from
[www.flaticon.com](https://www.flaticon.com/).

## :star: License

This project is licensed under [GNU General Public License 2.0 (or later)](LICENSE.md).
