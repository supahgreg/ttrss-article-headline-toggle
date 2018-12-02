<?php
class Article_Headline_Toggle extends Plugin {

  private $host;


  function about() {
    return [
      1.4, // version
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
  }


  function get_css() {
    return '#headlines-frame > div span.titleWrap { cursor: pointer; }';
  }


  function get_js() {
    return <<<'JS'
require(['dojo/ready'], (ready) => {
  ready(() => {
    PluginHost.register(PluginHost.HOOK_RUNTIME_INFO_LOADED, () => {
      // Do nothing if the user is forcing the expanded view
      if (App.getInitParam('cdm_expanded')) return;

      Headlines.click = (aEvent /*, aId, aInBody*/) => {
        const id = aEvent.target.dataset.articleId || aEvent.target.parentNode.dataset.articleId;

        if (!id || aEvent.ctrlKey) {
          return true;
        }

        if (document.getElementById(`RROW-${id}`).classList.contains('active')) {
          if (aEvent.target.tagName === 'A') {
            return true;
          }
          Article.cdmUnsetActive(aEvent);
        }
        else {
          Article.setActive(id);
          Article.cdmScrollToId(id);
        }

        return false;
      };
    });
  });
});
JS;
  }
}
?>
