<?php
class Article_Headline_Toggle extends Plugin {

  private $host;


  function about() {
    return [
      1.3, // version
      'Toggle article visibility by clicking on the headline', // description
      'wn', // author
      false, // is system
      'https://www.github.com/supahgreg/ttrss-article-headline-toggle', // more info URL
    ];
  }


  function api_version() {
    return 2;
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
    return '#headlines-frame > div span.titleWrap { cursor: pointer; }';
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
;(() => {
  // Do nothing if the user is forcing the expanded view
  if (getInitParam('cdm_expanded')) return;

  window.cdmClicked = (aEvent, aId, aInBody) => {
    const id = aEvent.target.dataset.articleId || aEvent.target.parentNode.dataset.articleId;

    if (!id || aEvent.ctrlKey) {
      return true;
    }

    if (document.getElementById(`RROW-${id}`).classList.contains('active')) {
      if (aEvent.target.tagName === 'A') {
        return true;
      }
      cdmCollapseActive(aEvent);
    }
    else {
      setActiveArticleId(id);
      cdmScrollToArticleId(id);
    }

    return false;
  };
})();
JS;
  }
}
?>
