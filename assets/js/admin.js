(function RDStationIntegration() {
  var SERVER_ORIGIN = 'https://skynet-sample.herokuapp.com';
  var CLIENT_ID = '12051950-222a-4513-bf02-638364768099';
  var REDIRECT_URL = 'https://skynet-sample.herokuapp.com/auth/callback';

  function oauthIntegration(message) {
    if (message.origin === SERVER_ORIGIN) {
      persist(message);
    }
  }

  function bindButton() {
    var button = document.querySelector('.rd-oauth-integration');

    button.addEventListener('click', function () {
      window.open('https://app-staging.rdstation.com.br/api/platform/auth?client_id=' + CLIENT_ID + '&;redirect_url=' + REDIRECT_URL, '_blank')
    })
  }

  function listenForMessage() {
    window.addEventListener('message', oauthIntegration);
  }

  function persist(message) {
    jQuery(document).ready(function ($) {
      var tokens = JSON.parse(message.data);
      var data = {
        action: 'rd-persist-tokens',
        accessToken: tokens.accessToken,
        refreshToken: tokens.refreshToken
      }

      jQuery.post(ajaxurl, data, function(response) {
        console.log(response);
      });
    });
  }

  function init() {
    bindButton();
    listenForMessage();
  }

  window.addEventListener('load', init);
})();
