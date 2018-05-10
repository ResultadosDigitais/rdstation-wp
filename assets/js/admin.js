(function RDStationIntegration() {
  var SERVER_ORIGIN = 'https://skynet-sample.herokuapp.com';
  var CLIENT_ID = '12051950-222a-4513-bf02-638364768099';
  var REDIRECT_URL = 'https://skynet-sample.herokuapp.com/auth/callback';

  function oauth_integration(message) {
    if (message.origin === SERVER_ORIGIN) {
      console.log(message);
    }
  }

  function bind_button() {
    var button = document.querySelector('.rd-oauth-integration');

    button.addEventListener('click', function () {
      window.open('https://app-staging.rdstation.com.br/api/platform/auth?client_id=' + CLIENT_ID + '&;redirect_url=' + REDIRECT_URL, '_blank')
    })
  }

  function listen_for_message() {
    window.addEventListener('message', oauth_integration);
  }

  function init() {
    bind_button();
    listen_for_message();
  }

  window.addEventListener('load', init);
})(); 