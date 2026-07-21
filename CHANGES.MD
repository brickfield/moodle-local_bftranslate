# Changes Release Notes

## Version 2.405.1

Security and hardening release, addressing an external code review.

## Bug fixes / Security

* Sanitise the translation values decoded from the posted state so raw HTML/JS
  can no longer be smuggled past the form-field cleaning into a saved language
  string (stored XSS) — decoded values are now cleaned with PARAM_CLEANHTML.
* Escape the source string and key columns in the comparison table (reflected XSS).
* Enforce the "allow core translation" setting on the decoded state path, not just
  the form dropdown.
* Type the `viewall` capability as `write` and declare its XSS/config risk.

## New features / Improvements

* The Azure Translator region is now a configurable setting (defaults to westeurope)
  instead of being hard-coded.
* Require `filelib.php` explicitly rather than relying on transitive loading.
* Remove unreachable code after the language-file download.

## Version 2.405.0

Marketplace release. Minimum supported Moodle raised to 4.5 LTS.

## New features / Improvements

* Supported Moodle range set to 4.5 through 5.2 (supported = [405, 502]).
* Minimum requirement raised to Moodle 4.5 / PHP 8.1.
* Release versioning moved to semantic versioning (2.405.0).
* CI matrix updated to test Moodle 4.5, 5.0, 5.1, and 5.2 on PHP 8.1 to 8.4.

## Version 1.401.08

## Bug fixes

* Updated plugin to logic check DeepL free or premium URL.

## Version 1.401.07

## Bug fixes

* Updated plugin to accept DeepL free API keys.

## Version 1.401.06

## Bug fixes

* CI error fix.

## Version 1.401.05

## New features / Improvements

* Better form validation for multiple plugins submission.
* Language string updates.
* French translation of plugin lang strings.
* Code style tidyups.
* Logging.

## Bug fixes

* Better string sanitisation.
* Download and Store submission options both now include existing strings.
