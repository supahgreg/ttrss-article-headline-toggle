<?php
class Article_Headline_Toggle extends Plugin {

  private $host;


  function about() {
    return Array(
        1.1 // version
      , "Toggle article visibility by clicking on the headline" // description
      , "wn" // author
      , false // is system
      , "https://www.github.com/supahgreg/ttrss-article-headline-toggle" // more info URL
    );
  }


  function init($host) {
    $this->host = $host;

    //$host->add_hook($host::HOOK_PREFS_TAB, $this);
    //$host->add_hook($host::HOOK_PREFS_TAB_SECTION, $this);
  }
  

  /**
   * Give a hint when hovering over an article's headline.
   */
  function get_css() {
    return "#headlines-frame > div > div.cdmHeader > span.titleWrap {"
         . " cursor: pointer;"
         . "}"
         ;
  }


  /**
   * Wrapping the cdmClicked function to toggle an article
   * when its headline is clicked.
   *
   * TODO: Add a pref to control whether pre-expanded articles
   * (from "Automatically expand articles in combined mode")
   * are eligible to be toggled.
   */
  function get_js() {
    return <<<'JS'
;(function(cdmClicked) {
  // Do nothing if the user is forcing the expanded view
  if (getInitParam("cdm_expanded")) return;

  var oldClicked = cdmClicked;

  function _cdmClicked(aEvent, aId) {
    var titleId = "RTITLE-" + aId
      , wasActive = $("RROW-" + aId).hasClassName("active")
      , ret = oldClicked.call(null, aEvent, aId)
      ;

    if (!aEvent.ctrlKey && aEvent.target.id === titleId) {
      if (wasActive)
        cdmCollapseArticle(null, aId);
      else
        cdmExpandArticle(aId);
    }

    return ret;
  }

  window.cdmClicked = _cdmClicked;
})(cdmClicked);
JS;
  }
}
?>

