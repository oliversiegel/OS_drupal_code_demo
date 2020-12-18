# Drupal Code Demo - Oliver Siegel

### Project Description:

## UI to easily add new and existing taxonomy terms from different vocabularies to nodes via AJAX

### Context & disclaimer:

This ajax taxonomy form feature could have possibly solved using a plugin/widget, but given the other project specific requirements I used services, blocks, and forms instead.

 * The code shown is part of a solution from a patent eligible app which needs to be treated confidentially
 * Seen is a heavily redacted work in progress which still requires some refactoring (mostly naming issues for scalability).
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

### How it works:

Display and submission of the form is handled through `AjaxTagsForm.php` which renders the form and does the neccessary ajax actions to dynamically display the taxonomy terms on the node after submission.

https://github.com/oliversiegel/OS_drupal_code_demo/blob/main/modules/custom/enolve_ajax_forms_CODE_DEMO/src/Form/AjaxTagsForm.php

To initiate an ajax request, the user navigates to the form to add tags, which submits POST requests onkeyup via JS to the `EntityReferenceController.php`

https://github.com/oliversiegel/OS_drupal_code_demo/blob/main/modules/custom/enolve_ajax_forms_CODE_DEMO/src/Controller/EntityReferenceController.php

This controller returns JSON which is interpreted by the JS files, so show the appropriate tags in the search form, which has been prepared accordingly.

To keep things scalable and re-use the "search" function and the `EntityReferenceController.php` also on other actions and entity searches, the form to be displayed will be dynamically generated based on `$params` by `public function getEnolveEntityReferenceForm($params)` located in `EnolveServices.php`.

https://github.com/oliversiegel/OS_drupal_code_demo/blob/main/modules/custom/enolve_ajax_forms_CODE_DEMO/src/EnolveServices.php

Finally, to be able to display subsections and actions, everything had been packaged into templates and blocks, for easy management.




