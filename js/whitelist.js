$('.ui.dropdown')
  .dropdown()
;
// $('.ui.dropdown.whitelist').addClass("disabled");
// $('.massive.ui.primary.button').addClass("disabled");
$('.ui.dropdown.whitelistclass')
  .dropdown({
    apiSettings: {
      // this url parses query server side and returns filtered results
      url: 'api/whitelist.php',
      cache: false
    },
    saveRemoteData: false,
    onChange(value) {
      $('.ui.dropdown.whitelist').removeClass("disabled");
      $('.ui.dropdown.whitelist').dropdown('clear');
      $('.massive.ui.primary.button').addClass("disabled");
      $('.ui.dropdown.whitelist')
        .dropdown({
          onChange(value) {
            $('.massive.ui.primary.button').removeClass("disabled");
          },
          apiSettings: {
            url: 'api/whitelist.php?q=' + value,
            cache: false
          },
          saveRemoteData: false,
        })
      ;
    }
  })
;