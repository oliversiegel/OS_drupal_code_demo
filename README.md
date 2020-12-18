# Drupal Code Demo - Oliver Siegel

### Project Description:

## UI to easily add new and existing taxonomy terms from different vocabularies to nodes via AJAX

### Context & disclaimer:

This ajax taxonomy form feature could have possibly solved using a plugin/widget, but given the other project specific requirements I went this route instead.

 * The code shown is part of a solution from a patent eligible app which needs to be treated confidentially
 * Seen is a heavily redacted work in progress which still requires some refactoring
 * Also this module relies heavily on the theme for the app
 * Unfortunately the theme can not be shown, as it would reveal too much about the patent.
 * Also none of the JS/jQuery files can be shown, as they would reveal too much about the patent. But it’s basically a bunch of show/hide/toggle commands attached to the DOM, and everything packaged into various classes and subclasses that mirror the structure of a node (with subsections and actions, as explained below).

### Requirements for the project:

 * Each node (“entry”) has several subsections.
 * Some of these subsections contain taxonomy terms from separate vocabularies.
 * Each subsection has actions.
 * One of these actions is to make visible a form that allows to add a new taxonomy term to the node in question.

 * It should be possible to add multiple taxonomy terms using one search field and submit them all with one press of a button. 
 * Also new tags should be added in this procedure.

Similar to adding tags on stack overflow, and similar to what’s being planned for future Drupal releases:

https://www.drupal.org/project/drupal/issues/3023298

Requirements are also that this is scalable for multiple different taxonomy vocabularies, and ties in seamlessly with other, similar actions, that constitute the core functionality of the app. (other actions and subsections)

