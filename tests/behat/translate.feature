@local @local_bftranslate
Feature: Access and validation for the Plugin Translator page
  In order to translate plugin language strings safely
  As an administrator
  I need the translation page to be admin-only, to report when no API is
  configured, and to reject incomplete submissions with named errors

  Scenario: An administrator can open the translation page
    Given I log in as "admin"
    When I visit "/local/bftranslate/index.php"
    Then I should see "Select plugin"
    And I should see "Select target language"

  # Note: direct-URL capability denial is verified in PHPUnit
  # (tests/access_test.php), because the required_capability error page carries a
  # stacktrace under developer debugging and Behat's exception hook fails the
  # visit step before any assertion runs.

  Scenario: The form reports when no translation API is configured
    Given I log in as "admin"
    When I visit "/local/bftranslate/index.php"
    Then I should see "No APIs are currently configured"

  Scenario: Submitting the form with nothing selected shows named validation errors
    Given the following config values are set as admin:
      | showlocaltest | 1 | local_bftranslate |
    And I log in as "admin"
    And I visit "/local/bftranslate/index.php"
    When I press "Translate"
    Then I should see "No plugin submitted"
    And I should see "No target language submitted"

  # Deferred: an end-to-end translate -> save workflow using the Local test API.
  # The target-language dropdown only lists installed languages, and a Behat site
  # has only English, so this scenario needs a target-language decision (use en, or
  # install a second language pack). Tracked in docs/state/behat-tests.md (D1/D2).
